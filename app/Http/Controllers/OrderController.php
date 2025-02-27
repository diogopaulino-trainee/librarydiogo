<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\DeliveryAddress;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Traits\Loggable;

class OrderController extends Controller
{
    use Loggable;

    public function index(Request $request)
    {
        abort_if(!auth()->user()->hasRole('Citizen'), 403, 'Access denied.');

        $query = Order::where('user_id', Auth::id())->with('items.book');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('id', 'like', "%$search%");
        }

        if ($request->has('status') && in_array($request->status, ['pending', 'paid', 'canceled'])) {
            $query->where('status', $request->status);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        // Logando o acesso ao módulo de pedidos
        $this->logAction('Order', 'Viewing orders list', 'User viewed their order list. Search: ' . ($request->search ?? 'N/A') . ', Status: ' . ($request->status ?? 'N/A'), Auth::id());

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        abort_if(!auth()->user()->hasRole('Citizen'), 403, 'Access denied.');

        $cartItems = CartItem::where('user_id', Auth::id())
            ->with('book')
            ->get();

        if ($cartItems->isEmpty()) {
            // Logando quando o carrinho está vazio
            $this->logAction('Order', 'Attempting to create an order', 'User attempted to create an order, but the cart is empty.', Auth::id());
            
            return redirect()->route('cart.index')
                             ->with('error', 'Your cart is empty.');
        }
        // Logando quando o usuário pode criar o pedido
        $this->logAction('Order', 'Creating an order', 'User is creating an order with items in the cart.', Auth::id());

        return view('orders.create', compact('cartItems'));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasRole('Citizen'), 403, 'Access denied.');

        $request->validate([
            'street'   => 'required',
            'city'     => 'required',
            'zip_code' => 'required',
            'country'  => 'required',
        ]);

        $cartItems = CartItem::where('user_id', Auth::id())
                            ->with('book')
                            ->get();

        if ($cartItems->isEmpty()) {
            // Logando quando o carrinho está vazio
            $this->logAction('Order', 'Attempting to store an order', 'User attempted to create an order, but the cart is empty.', Auth::id());
           
            return redirect()->route('cart.index')
                            ->with('error', 'Your cart is empty.');
        }

        // Logando quando o usuário tem itens no carrinho e pode realizar o pedido
        $this->logAction('Order', 'Creating order', 'User is creating an order with items in the cart. Total price: ' . $cartItems->sum(fn ($item) => $item->book->price), Auth::id());

        $totalPrice = $cartItems->sum(fn ($item) => $item->book->price);

        $address = DeliveryAddress::updateOrCreate(
            ['user_id' => Auth::id()],
            $request->only(['street', 'city', 'zip_code', 'country'])
        );

        $order = Order::create([
            'user_id'     => Auth::id(),
            'total_price' => $totalPrice,
            'status'      => 'pending',
            'address_id'  => $address->id
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id'      => $order->id,
                'book_id'       => $item->book_id,
                'price_at_time' => $item->book->price,
            ]);
        }

        CartItem::where('user_id', Auth::id())->delete();

        // Logando o sucesso na criação do pedido e esvaziamento do carrinho
        $this->logAction('Order', 'Order stored successfully', 'User successfully created an order. Order ID: ' . $order->id . '. Cart items cleared.', Auth::id());

        return redirect()->route('orders.payment', $order->id);
    }

    public function payment(Order $order)
    {
        abort_if(!auth()->user()->hasRole('Citizen'), 403, 'Access denied.');

        // Logando a tentativa de pagamento
        $this->logAction('Order', 'Attempting to process payment', 'User is attempting to process payment for Order ID: ' . $order->id, Auth::id());

        // Impedir pagamento de encomendas canceladas
        if ($order->status !== 'pending') {

            $this->logAction('Order', 'Payment attempt failed', 'User tried to pay for an order that is no longer available for payment. Order ID: ' . $order->id, Auth::id());

            return redirect()->route('orders.index')
                ->with('error', 'This order is no longer available for payment.');
        }

        // Verificar se todos os livros ainda estão disponíveis (com lock para evitar concorrência)
        foreach ($order->items as $item) {
            $book = $item->book()->lockForUpdate()->first();
            if ($book->status !== 'available') {

                $this->logAction('Order', 'Payment attempt failed', 'One or more books in the order are no longer available for payment. Order ID: ' . $order->id, Auth::id());

                return redirect()->route('orders.index')
                    ->with('error', 'One or more books in this order are no longer available.');
            }
        }

        // Logando que todos os livros estão disponíveis para pagamento
        $this->logAction('Order', 'Books available for payment', 'All books in the order are available. Proceeding with payment. Order ID: ' . $order->id, Auth::id());

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $lineItems = $order->items->map(function ($item) {
            return [
                'price_data' => [
                    'currency'     => 'eur',
                    'product_data' => ['name' => $item->book->title],
                    'unit_amount'  => $item->price_at_time * 100,
                ],
                'quantity' => 1,
            ];
        })->toArray();

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => $lineItems,
            'mode'                 => 'payment',
            'success_url'          => route('orders.success', $order->id),
        ]);

        // Logando a criação da sessão de pagamento
        $this->logAction('Order', 'Stripe session created', 'A payment session was successfully created for Order ID: ' . $order->id, Auth::id());

        return redirect($session->url);
    }

    public function success(Order $order)
    {
        abort_if(!auth()->user()->hasRole('Citizen'), 403, 'Access denied.');

        // Logando a tentativa de marcar o pagamento como bem-sucedido
        $this->logAction('Order', 'Attempting to process payment', 'User is attempting to mark payment as successful for Order ID: ' . $order->id, Auth::id());

        // Evitar que o pagamento seja processado se a encomenda não estiver pendente
        if ($order->status !== 'pending') {

            // Logando que a encomenda não está mais disponível para processamento
            $this->logAction('Order', 'Payment attempt failed', 'User tried to process payment for an order that is no longer available. Order ID: ' . $order->id, Auth::id());
            
            return redirect()->route('orders.index')
                ->with('error', 'This order can no longer be processed.');
        }

        $order->update(['status' => 'paid']);
        
        // Logando que o pagamento foi bem-sucedido
        $this->logAction('Order', 'Payment successful', 'Payment was successfully processed for Order ID: ' . $order->id, Auth::id());

        foreach ($order->items as $item) {
            $item->book->update(['status' => 'unavailable']);
        }

         // Logando a atualização dos livros para 'unavailable'
        foreach ($order->items as $item) {
            $this->logAction('Book', 'Marking book as unavailable', 'Book ID ' . $item->book->id . ' has been marked as unavailable due to order payment. Order ID: ' . $order->id, Auth::id());
        }
    
        return redirect()->route('orders.index')
                         ->with('success', 'Payment successful.');
    }

    public function show(Order $order)
    {
        abort_if(!auth()->user()->hasRole('Citizen'), 403, 'Access denied.');

        // Logando a tentativa de visualização da ordem
        $this->logAction('Order', 'Attempting to view order details', 'User is attempting to view details for Order ID: ' . $order->id, Auth::id());
        
        if ($order->user_id !== Auth::id()) {
            // Logando a falha de acesso devido à tentativa de um usuário acessar uma ordem de outro usuário
            $this->logAction('Order', 'Access Denied', 'User attempted to access an order that does not belong to them. Order ID: ' . $order->id, Auth::id());

            abort(403, 'Access Denied.');
        }

        $order->load('items.book', 'address');

        // Logando o sucesso no carregamento da ordem
        $this->logAction('Order', 'Order details loaded', 'Order details loaded successfully for Order ID: ' . $order->id, Auth::id());

        return view('orders.show', compact('order'));
    }
}
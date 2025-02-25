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

class OrderController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->hasRole('Citizen'), 403, 'Access denied.');

        $orders = Order::where('user_id', Auth::id())->with('items.book')->get();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        abort_if(!auth()->user()->hasRole('Citizen'), 403, 'Access denied.');

        $cartItems = CartItem::where('user_id', Auth::id())
            ->with('book')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                             ->with('error', 'Your cart is empty.');
        }
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
            return redirect()->route('cart.index')
                            ->with('error', 'Your cart is empty.');
        }

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

        return redirect()->route('orders.payment', $order->id);
    }

    public function payment(Order $order)
    {
        abort_if(!auth()->user()->hasRole('Citizen'), 403, 'Access denied.');

        // Impedir pagamento de encomendas canceladas
        if ($order->status !== 'pending') {
            return redirect()->route('orders.index')
                ->with('error', 'This order is no longer available for payment.');
        }

        // Verificar se todos os livros ainda estão disponíveis (com lock para evitar concorrência)
        foreach ($order->items as $item) {
            $book = $item->book()->lockForUpdate()->first();
            if ($book->status !== 'available') {
                return redirect()->route('orders.index')
                    ->with('error', 'One or more books in this order are no longer available.');
            }
        }

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
        return redirect($session->url);
    }

    public function success(Order $order)
    {
        abort_if(!auth()->user()->hasRole('Citizen'), 403, 'Access denied.');

        // Evitar que o pagamento seja processado se a encomenda não estiver pendente
        if ($order->status !== 'pending') {
            return redirect()->route('orders.index')
                ->with('error', 'This order can no longer be processed.');
        }

        $order->update(['status' => 'paid']);
        
        foreach ($order->items as $item) {
            $item->book->update(['status' => 'unavailable']);
        }
    
        return redirect()->route('orders.index')
                         ->with('success', 'Payment successful.');
    }

    public function show(Order $order)
    {
        abort_if(!auth()->user()->hasRole('Citizen'), 403, 'Access denied.');
        
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Access Denied.');
        }

        $order->load('items.book', 'address');

        return view('orders.show', compact('order'));
    }
}
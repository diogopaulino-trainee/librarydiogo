<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Traits\Loggable;

class AdminOrderController extends Controller
{
    use Loggable;

    public function index(Request $request)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

        $query = Order::with('user', 'items.book')->orderBy('created_at', 'desc');

        if ($request->has('status') && in_array($request->status, ['pending', 'paid', 'canceled'])) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                ->orWhereHas('user', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%$search%");
                });
            });
        }

        $orders = $query->get();

        // Logando o acesso ao módulo de pedidos
        $this->logAction('Admin', 'Viewing orders list', 'Accessing the orders list.');

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

        $order->load('user', 'items.book', 'address');

        // Logando a visualização do pedido específico
        $this->logAction('Admin', 'Viewing order details', 'Accessing order details for order ID ' . $order->id, $order->id);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

        $request->validate(['status' => 'required|in:pending,paid,cancelled']);

        // Impedir que um pedido cancelado seja alterado para "paid"
        if ($order->status === 'cancelled' && $request->status === 'paid') {
            return redirect()->route('admin.orders.index')
                ->with('error', 'A cancelled order cannot be marked as paid.');
        }

        // Guardar o status anterior
        $previousStatus = $order->status;

        // Descrição detalhada para o log
        $logDescription = "Changing order status from {$previousStatus} to {$request->status}.";

        // Logando a mudança de status da encomenda
        $this->logAction('Admin', 'Updating order status', $logDescription, $order->id);

        if ($request->status === 'cancelled' && $previousStatus === 'paid') {
            // Se a encomenda foi paga e agora é cancelada, verifica se os livros ainda estão indisponíveis
            foreach ($order->items as $item) {
                $bookStillOrdered = OrderItem::where('book_id', $item->book_id)
                    ->whereHas('order', function ($query) {
                        $query->where('status', 'paid');
                    })
                    ->exists();

                if (!$bookStillOrdered) {
                    $item->book->update(['status' => 'available']);
                }
            }
        }

        if ($request->status === 'pending' && $previousStatus === 'paid') {
            // Se a encomenda estava paga e volta para pendente, os livros devem ficar disponíveis novamente
            foreach ($order->items as $item) {
                $item->book->update(['status' => 'available']);
            }
        }

        if ($request->status === 'paid' && $previousStatus !== 'paid') {
            // Se a encomenda está a ser paga pela primeira vez ou voltou a ser paga, verificar se os livros ainda estão disponíveis
            foreach ($order->items as $item) {
                $book = $item->book()->lockForUpdate()->first();
                if ($book->status === 'available') {
                    $book->update(['status' => 'unavailable']);
                } else {
                    return redirect()->route('admin.orders.index')
                        ->with('error', 'One or more books in this order are no longer available.');
                }
            }
        }

        $order->update(['status' => $request->status]);

        return redirect()->route('admin.orders.index')->with('success', 'Order status updated successfully.');
    }
}

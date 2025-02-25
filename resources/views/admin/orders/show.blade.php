<x-app-layout>
    <x-slot name="header">
        <h2>Order Details (Admin)</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6 border border-blue-500">

                <div class="mb-4 text-lg">
                    <h3 class="text-xl font-semibold text-gray-800">
                        Order #{{ $order->id }}
                    </h3>
                    <p class="text-gray-700"><strong>Customer:</strong> {{ $order->user->name }} ({{ $order->user->email }})</p>
                    
                    <p class="text-gray-700"><strong>Status:</strong> 
                        <span class="inline-block px-3 py-1 rounded-full text-base font-medium
                            {{ match($order->status) {
                                'paid' => 'bg-green-100 text-green-600',
                                'pending' => 'bg-yellow-100 text-yellow-600',
                                'cancelled' => 'bg-red-100 text-red-600',
                                default => 'bg-gray-100 text-gray-600'
                            } }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                
                    <p class="text-gray-700"><strong>Total:</strong> 
                        {{ number_format($order->total_price, 2, ',', '.') }} €
                    </p>
                    <p class="text-gray-700"><strong>Date:</strong> 
                        {{ $order->created_at->format('d M, Y') }}
                    </p>
                </div>

                @if ($order->address)
                    <div class="bg-blue-100 p-4 rounded-md mb-4 text-base">
                        <h4 class="font-semibold text-gray-800 mb-2">Delivery Address</h4>
                        <p class="text-gray-700">
                            {{ $order->address->street }}<br>
                            {{ $order->address->city }}, {{ $order->address->zip_code }}<br>
                            {{ $order->address->country }}
                        </p>
                    </div>
                @endif

                <h4 class="text-lg text-gray-700 font-semibold border-b pb-2 mb-4">Order Items</h4>
                @if($order->items->isEmpty())
                    <p class="text-gray-500 italic">No items found.</p>
                @else
                    <table class="min-w-full text-lg border rounded-lg shadow-md">
                        <thead class="bg-blue-600 text-white">
                            <tr>
                                <th class="px-4 py-2">Book</th>
                                <th class="px-4 py-2 text-right">Price at Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr class="border-b border-gray-300 last:border-b-2 last:border-blue-500 hover:bg-blue-500 hover:text-white group">
                                    <td class="px-4 py-2">
                                        <a href="{{ route('books.show', $item->book->id) }}" 
                                           class="text-blue-600 hover:underline hover:text-blue-800 group-hover:text-white">
                                            {{ $item->book->title ?? 'N/A' }}
                                        </a>
                                    </td>   
                                    <td class="px-4 py-2 text-right">
                                        {{ number_format($item->price_at_time, 2, ',', '.') }} €
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                <h4 class="text-lg text-gray-700 font-semibold border-b pb-2 mb-4 mt-6">Update Order Status</h4>
                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="mt-4 text-lg">
                    @csrf
                    @method('PUT')
                    <div class="flex items-center gap-4">
                        <select name="status" class="border rounded p-2 text-lg w-40">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                            Update Status
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-lg">
                    <a href="{{ route('admin.orders.index') }}"
                       class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                       Back to Orders
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

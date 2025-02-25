<x-app-layout>
    <x-slot name="header">
        <h2>Order Details</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6 border border-blue-500">
                @if (session('success'))
                    <div class="max-w-4xl mx-auto mt-12 mb-2">
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-md" role="alert">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-700 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                                </svg>
                                <strong class="font-bold text-green-800">Success!</strong>
                                <span class="ml-2">{{ session('success') }}</span>
                            </div>
                            <button onclick="this.parentElement.style.display='none'" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg class="fill-current h-6 w-6 text-green-700" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <title>Close</title>
                                    <path d="M14.348 5.652a1 1 0 00-1.414 0L10 8.586 7.066 5.652a1 1 0 10-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 11.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934a1 1 0 000-1.414z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                @if (session('error')) 
                    <div class="max-w-4xl mx-auto mt-12 mb-2">
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-md" role="alert">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-red-700 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <strong class="font-bold text-red-800">Error!</strong>
                                <span class="ml-2">{{ session('error') }}</span>
                            </div>    
                            <button onclick="this.parentElement.style.display='none'" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg class="fill-current h-6 w-6 text-red-700" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <title>Close</title>
                                    <path d="M14.348 5.652a1 1 0 00-1.414 0L10 8.586 7.066 5.652a1 1 0 10-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 11.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934a1 1 0 000-1.414z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="max-w-4xl mx-auto mt-12 mb-2">
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-md" role="alert">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-red-700 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <strong class="font-bold text-red-800">Validation Error</strong>
                            </div>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button onclick="this.parentElement.style.display='none'" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg class="fill-current h-6 w-6 text-red-700" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <title>Close</title>
                                    <path d="M14.348 5.652a1 1 0 00-1.414 0L10 8.586 7.066 5.652a1 1 0 10-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 11.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934a1 1 0 000-1.414z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif
                
                <div class="mb-4 mt-4 text-lg text-gray-700"> 
                    <h3 class="text-xl font-semibold text-gray-800">
                        Order #{{ $order->id }}
                    </h3>
                    <p>
                        <span class="font-semibold text-gray-800">Status:</span> {{ ucfirst($order->status) }}
                    </p>
                    <p>
                        <span class="font-semibold text-gray-800">Total:</span> 
                        {{ number_format($order->total_price, 2, ',', '.') }} €
                    </p>
                    <p>
                        <span class="font-semibold text-gray-800">Date:</span> 
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

                <h4 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Order Items</h4>
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

                @if($order->status === 'pending')
                    <div class="mt-6 text-lg">
                        <a href="{{ route('orders.payment', $order->id) }}"
                           class="inline-block bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                           Continue to Payment
                        </a>
                    </div>
                @endif

                <div class="mt-6 text-lg">
                    <a href="{{ route('orders.index') }}"
                       class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                       Back to Orders
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

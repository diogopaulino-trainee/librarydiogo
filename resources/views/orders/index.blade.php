<x-app-layout>
    <x-slot name="header">
        <h2>{{ __('My Orders') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg border border-blue-500 p-6">
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
                @if ($orders->isEmpty())
                    <p class="text-gray-500 text-center text-lg">
                        You have no orders yet. 
                        <a href="{{ route('books.index') }}" class="text-blue-500 hover:text-blue-700">
                            Browse Books
                        </a>
                    </p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full mt-4 text-lg border rounded-lg shadow-md">
                            <thead class="bg-blue-600 text-white uppercase tracking-wider">
                                <tr>
                                    <th class="px-4 py-2 text-left">Order #</th>
                                    <th class="px-4 py-2 text-right">Total</th>
                                    <th class="px-4 py-2 text-center">Status</th>
                                    <th class="px-4 py-2 text-right">Date</th>
                                    <th class="px-4 py-2 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($orders as $order)
                                    <tr class="border-b border-gray-300 last:border-b-2 last:border-blue-500 hover:bg-blue-500 hover:text-white group">
                                        <td class="px-4 py-2 font-semibold text-gray-800 group-hover:text-white">
                                            #{{ $order->id }}
                                        </td>
                                    
                                        <td class="px-4 py-2 text-right text-gray-700 group-hover:text-white">
                                            {{ number_format($order->total_price, 2, ',', '.') }} €
                                        </td>
                                    
                                        <td class="px-4 py-2 text-center">
                                            @php
                                                $bgColor = match($order->status) {
                                                    'paid'     => 'bg-green-100 text-green-600 group-hover:bg-green-500 group-hover:text-white',
                                                    'pending'  => 'bg-yellow-100 text-yellow-600 group-hover:bg-yellow-500 group-hover:text-white',
                                                    'cancelled','canceled' => 'bg-red-100 text-red-600 group-hover:bg-red-500 group-hover:text-white',
                                                    default    => 'bg-gray-100 text-gray-600 group-hover:bg-gray-500 group-hover:text-white'
                                                };
                                            @endphp
                                            <span class="inline-block px-3 py-1 rounded-full text-base font-medium transition {{ $bgColor }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                    
                                        <td class="px-4 py-2 text-right text-gray-700 group-hover:text-white">
                                            {{ $order->created_at->format('d M, Y') }}
                                        </td>
                                    
                                        <td class="px-4 py-2 text-right">
                                            <a href="{{ route('orders.show', $order->id) }}"
                                                class="inline-flex items-center px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-700 transition">
                                                Details
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

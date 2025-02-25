<x-app-layout>
    <x-slot name="header">
        <h2>{{ __('Shopping Cart') }}</h2>
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

                @if ($cartItems->isEmpty())
                    <p class="text-gray-500 text-center text-lg">Your cart is empty.</p>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-lg table-auto mt-4 border border-gray-300 rounded-lg shadow-md">
                        <thead class="bg-blue-600 text-white">
                            <tr>
                                <th class="px-4 py-2">Book</th>
                                <th class="px-4 py-2">Price</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                                <tr class="border-b border-gray-300 last:border-b-2 last:border-blue-500 hover:bg-blue-500 hover:text-white group">
                                    <td class="px-4 py-2">{{ $item->book->title }}</td>
                                    <td class="px-4 py-2 text-center font-semibold whitespace-nowrap">{{ number_format($item->book->price, 2, ',', '.') }} &euro;</td>
                                    <td class="px-4 py-2 text-center">
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex items-center justify-center mx-auto text-red-500 hover:text-red-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M3 6h18"></path>
                                                    <path d="M8 6v14"></path>
                                                    <path d="M16 6v14"></path>
                                                    <path d="M5 6h14l-1 14H6L5 6z"></path>
                                                    <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                <div class="mt-6 flex justify-between">
                    <a href="{{ route('books.index') }}" class="bg-blue-500 text-white text-lg px-4 py-2 rounded hover:bg-blue-700">Continue Shopping</a>
                    @if(!$cartItems->isEmpty())
                        <a href="{{ route('orders.create') }}" class="bg-green-500 text-white text-lg px-4 py-2 rounded hover:bg-green-700">Proceed to Checkout</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2>{{ __('Checkout') }}</h2>
    </x-slot>

    @php
        $cartItems = \App\Models\CartItem::where('user_id', auth()->id())
            ->with('book')
            ->get();

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->book->price;
        }
    @endphp

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg border border-blue-500">

                @if($cartItems->isEmpty())
                    <div class="p-6 text-center text-gray-600">
                        <p>Your cart is empty. 
                           <a href="{{ route('books.index') }}" class="text-blue-500">Go back</a>
                        </p>
                    </div>
                @else
                    <div class="overflow-x-auto p-6">
                        <table class="min-w-full mt-4 text-lg border rounded-lg shadow-md">
                            <thead class="bg-blue-600 text-white">
                                <tr>
                                    <th class="px-4 py-2 text-left">Book</th>
                                    <th class="px-4 py-2 text-right">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                <tr class="border-b border-gray-300 last:border-b-2 last:border-blue-500 hover:bg-blue-500 hover:text-white group">
                                    <td class="px-4 py-2 group-hover:text-white">
                                        <div class="font-semibold text-gray-800 group-hover:text-white">
                                            {{ $item->book->title }}
                                        </div>
                                        <div class="text-sm text-gray-500 group-hover:text-white">
                                            ISBN: {{ $item->book->isbn }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 text-right group-hover:text-white">
                                        {{ number_format($item->book->price, 2, ',', '.') }} €
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td class="px-4 py-2 font-bold text-right">
                                        Total:
                                    </td>
                                    <td class="px-4 py-2 text-right font-bold text-gray-800">
                                        {{ number_format($total, 2, ',', '.') }} €
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="p-6">
                        <form action="{{ route('orders.store') }}" method="POST" class="space-y-4">
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
                            @csrf

                            <div class="mt-4">
                                <label class="block text-lg font-medium text-gray-700">Street</label>
                                <input type="text" name="street" class="w-full text-lg border p-2 rounded">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-lg font-medium text-gray-700">City</label>
                                    <input type="text" 
                                           name="city"
                                           class="border p-2 w-full text-lg"
                                           pattern="[A-Za-z\s]+"
                                           title="Only letters are allowed"
                                           value="{{ old('city') }}">
                                </div>
                                <div>
                                    <label for="zip_code" class="block text-lg font-medium text-gray-700">Zip Code</label>
                                    <input type="text" 
                                        id="zip_code"
                                        name="zip_code" 
                                        maxlength="8" 
                                        class="border p-2 w-full text-lg"
                                        placeholder="e.g. 1234-567"
                                        pattern="^\d{4}-\d{3}$"
                                        title="Use the format 0000-000"
                                        value="{{ old('zip_code') }}">
                                </div>
                            </div>

                            <div>
                                <label class="block text-lg font-medium text-gray-700">Country</label>
                                <input type="text" 
                                       name="country" 
                                       class="border p-2 w-full text-lg"
                                       pattern="[A-Za-z\s]+"
                                       title="Only letters are allowed"
                                       value="{{ old('country') }}">
                            </div>

                            <div class="flex justify-end pt-4">
                                <button type="submit" class="bg-green-500 text-white text-lg px-6 py-2 rounded hover:bg-green-700">
                                    Place Order
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <script>
        document.getElementById('zip_code').addEventListener('input', function () {
          let input = this.value.replace(/\D/g, '');
          
          input = input.substring(0,7);
      
          if (input.length > 4) {
            this.value = input.substring(0,4) + '-' + input.substring(4);
          } else {
            this.value = input;
          }
        });
      </script>
</x-app-layout>

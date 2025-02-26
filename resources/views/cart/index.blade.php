<x-app-layout>
    <x-slot name="header">
        <h2>{{ __('Shopping Cart') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6 border border-blue-500">
                
                @if ($cartItems->isEmpty())
                    <div class="text-center text-gray-600 text-lg">
                        <p class="mt-4">Your cart is empty.</p>
                        <a href="{{ route('books.index') }}" class="mt-4 inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700 transition-all">Browse Books</a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <div class="flex justify-between items-center mb-4">
                            <p class="text-lg text-gray-600">
                                TOTAL ({{ count($cartItems) }} Items) 
                                <span class="font-bold text-blue-700">{{ number_format($cartItems->sum(fn($item) => $item->book->price), 2, ',', '.') }} €</span>
                            </p>
                            
                            <button id="clearCartBtn" class="inline-flex items-center justify-center gap-2 bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-700 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18"></path>
                                    <path d="M8 6v14"></path>
                                    <path d="M16 6v14"></path>
                                    <path d="M5 6h14l-1 14H6L5 6z"></path>
                                    <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
                                </svg>
                                <span class="font-medium">Clear Cart</span>
                            </button>
                        </div>
                        
                        <table class="w-full text-lg table-auto mt-4 border border-gray-300 rounded-lg shadow-md">
                            <thead class="bg-blue-600 text-white">
                                <tr>
                                    <th class="px-4 py-2 text-left">Book</th>
                                    <th class="px-4 py-2 text-center">Price</th>
                                    <th class="px-4 py-2 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                    <tr class="border-b border-gray-300 last:border-b-2 last:border-blue-500 hover:bg-blue-100 transition-all">
                                        <td class="px-4 py-2 flex items-start gap-4 text-wrap">
                                            @if($item->book->cover_image)
                                                <img src="{{ asset(str_starts_with($item->book->cover_image, 'images/') ? $item->book->cover_image : 'images/' . $item->book->cover_image) }}" 
                                                    alt="Cover Image"
                                                    class="w-16 h-16 rounded-md object-cover">
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M6 6h15l-1.5 9H7.5L6 6z"></path>
                                                    <path d="M9 22h0"></path>
                                                    <path d="M20 22h0"></path>
                                                    <path d="M6 10h15"></path>
                                                </svg>
                                            @endif
                                            <div class="text-sm sm:text-base w-full text-left break-words">
                                                <span class="font-semibold">{{ $item->book->title }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-2 text-center font-semibold text-blue-700">{{ number_format($item->book->price, 2, ',', '.') }} €</td>
                                        <td class="px-4 py-2 text-center">
                                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 transition-all inline-flex justify-center items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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

                    <div class="mt-6 flex flex-col sm:flex-row justify-between gap-4">
                        <a href="{{ route('books.index') }}" class="bg-blue-500 text-white text-lg px-4 py-2 rounded hover:bg-blue-700 transition-all text-center">Continue Shopping</a>
                        <a href="{{ route('orders.create') }}" class="bg-green-500 text-white text-lg px-4 py-2 rounded hover:bg-green-700 transition-all text-center">Proceed to Checkout</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById("clearCartBtn").addEventListener("click", function () {
            Swal.fire({
                title: "Are you sure?",
                text: "This will remove all items from your cart!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, clear it!",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ route('cart.clear') }}", {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json"
                        }
                    }).then(() => {
                        Swal.fire("Cleared!", "Your cart has been emptied.", "success");
                        setTimeout(() => location.reload(), 1000);
                    }).catch(error => console.error("Error clearing cart:", error));
                }
            });
        });
    </script>
</x-app-layout>

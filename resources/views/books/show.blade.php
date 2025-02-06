<x-app-layout>
    <x-slot name="header">
        <h2>{{ ('Book Details') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6 border border-blue-500 relative">
                @auth
                    <button id="favorite-btn" 
                        class="absolute top-3 right-3 bg-white p-4 rounded-full shadow-xl transition duration-300 group"
                        onclick="toggleFavorite({{ $book->id }})">
                        <svg id="favorite-icon" xmlns="http://www.w3.org/2000/svg" 
                            class="h-8 w-8 transition duration-300 group-hover:fill-red-600" 
                            viewBox="0 0 24 24" 
                            fill="{{ auth()->user()->favorites->contains($book->id) ? 'red' : 'none' }}" 
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" 
                                d="M12 21l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.18L12 21z" />
                        </svg>
                    </button>
                @endauth

                <div class="mb-4 text-center">
                    <img src="{{ asset(str_starts_with($book->cover_image, 'images/') ? $book->cover_image : 'images/' . $book->cover_image) }}" 
                         alt="Cover Image" 
                         class="w-48 h-64 mx-auto object-cover rounded-lg shadow-md">
                </div>

                <div class="grid grid-cols-1 gap-4 text-gray-800">
                    <div><strong>ISBN:</strong> {{ $book->isbn }}</div>
                    <div><strong>Title:</strong> {{ $book->title }}</div>
                    <div>
                        <strong>Authors:</strong> 
                        @if($book->authors->isNotEmpty())
                            @foreach ($book->authors as $author)
                                {{ $author->name }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        @else
                            N/A
                        @endif
                    </div>
                    <div><strong>Publisher:</strong> {{ $book->publisher->name ?? 'N/A' }}</div>
                    <div>
                        <strong>Bibliography:</strong>
                        @auth
                            {{ $book->bibliography }}
                        @endauth
                        @guest
                            {{ \Illuminate\Support\Str::limit($book->bibliography, 20) }} 
                            <span class="text-gray-500 italic">Available only for registered users</span>
                        @endguest
                    </div>
                    <div><strong>Price:</strong> {{ number_format($book->price, 2, ',', '.') }} €</div>
                    <div class="text-sm italic text-gray-600">
                        <strong>Added By:</strong> {{ $book->user->name }}
                    </div>
                </div>

                <div class="mt-6 flex justify-between">
                    <a href="{{ route('books.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Back to List</a>
                    <a href="{{ route('books.edit', $book) }}" class="bg-yellow-400 text-white px-4 py-2 rounded hover:bg-yellow-600">Edit Book</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleFavorite(bookId) {
            fetch(`/favorites/toggle/${bookId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                let icon = document.getElementById('favorite-icon');
                icon.setAttribute("fill", data.favorited ? "red" : "none");
            })
            .catch(error => console.error('Erro ao marcar/desmarcar favorito:', error));
        }
    </script>
</x-app-layout>

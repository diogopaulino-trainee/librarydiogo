<x-app-layout>
    <x-slot name="header">
        <h2>{{ ('Book Details') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6 border border-blue-500">
                <div class="mb-4 text-center">
                    <img src="{{ asset('images/' . $book->cover_image) }}" alt="Cover Image" class="w-48 h-64 mx-auto object-cover rounded-lg shadow-md">
                </div>

                <div class="grid grid-cols-1 gap-4 text-gray-800">
                    <div><strong>ISBN:</strong> {{ $book->isbn }}</div>
                    <div><strong>Title:</strong> {{ $book->title }}</div>
                    <div><strong>Author:</strong> {{ $book->author->name ?? 'N/A' }}</div>
                    <div><strong>Publisher:</strong> {{ $book->publisher->name ?? 'N/A' }}</div>
                    <div><strong>Bibliography:</strong> {{ $book->bibliography ?? 'N/A' }}</div>
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
</x-app-layout>

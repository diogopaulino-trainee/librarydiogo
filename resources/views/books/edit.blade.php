<x-app-layout>
    <x-slot name="header">
        <h2>{{ ('Edit Book') }}</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-8 p-6 bg-white shadow-md rounded-lg border border-blue-200">
        <form action="{{ route('books.update', $book->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- ISBN -->
            <div class="form-control">
                <label for="isbn" class="label text-blue-500 font-semibold">ISBN</label>
                <input type="text" name="isbn" id="isbn" value="{{ old('isbn', $book->isbn) }}"
                    class="w-full p-3 rounded-md bg-blue-900 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 
                    @error('isbn') border-red-500 @enderror">
                @error('isbn')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Title -->
            <div class="form-control">
                <label for="title" class="label text-blue-500 font-semibold">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $book->title) }}"
                    class="w-full p-3 rounded-md bg-blue-900 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 
                    @error('title') border-red-500 @enderror">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bibliography -->
            <div class="form-control">
                <label for="bibliography" class="label text-blue-500 font-semibold">Bibliography</label>
                <textarea name="bibliography" id="bibliography"
                    class="w-full p-3 rounded-md bg-blue-900 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 
                    @error('bibliography') border-red-500 @enderror">{{ old('bibliography', $book->bibliography) }}</textarea>
                @error('bibliography')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Cover Image -->
            <div class="form-control">
                <label for="cover_image" class="label text-blue-500 font-semibold">Cover Image</label>
                <input type="file" name="cover_image" id="cover_image"
                    class="file-input file-input-bordered w-full bg-blue-900 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 
                    @error('cover_image') border-red-500 @enderror" accept="image/*">
                @if ($book->cover_image)
                    <img src="{{ asset(str_starts_with($book->cover_image, 'images/') ? $book->cover_image : 'images/' . $book->cover_image) }}" alt="Current Cover" class="w-32 mt-2 rounded shadow-md">
                @endif
                @error('cover_image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Price -->
            <div class="form-control">
                <label for="price" class="label text-blue-500 font-semibold">Price (€)</label>
                <input type="number" name="price" id="price" step="0.01" value="{{ old('price', $book->price) }}"
                    class="w-full p-3 rounded-md bg-blue-900 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 
                    @error('price') border-red-500 @enderror">
                @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Publisher -->
            <div class="form-control">
                <label for="publisher_id" class="label text-blue-500 font-semibold">Publisher</label>
                <select name="publisher_id" id="publisher_id"
                    class="w-full p-3 rounded-md bg-blue-900 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 
                    @error('publisher_id') border-red-500 @enderror">
                    @foreach ($publishers as $publisher)
                        <option value="{{ $publisher->id }}" {{ old('publisher_id', $book->publisher_id) == $publisher->id ? 'selected' : '' }}>
                            {{ $publisher->name }}
                        </option>
                    @endforeach
                </select>
                @error('publisher_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Author -->
            <div class="form-control">
                <label for="author_id" class="label text-blue-500 font-semibold">Author</label>
                <select name="author_id" id="author_id"
                    class="w-full p-3 rounded-md bg-blue-900 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 
                    @error('author_id') border-red-500 @enderror">
                    @foreach ($authors as $author)
                        <option value="{{ $author->id }}" {{ old('author_id', $book->author_id) == $author->id ? 'selected' : '' }}>
                            {{ $author->name }}
                        </option>
                    @endforeach
                </select>
                @error('author_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit"
                    class="w-full md:w-auto px-6 py-3 bg-blue-500 text-white font-bold rounded-md hover:bg-blue-400 transition duration-300">
                    Update Book
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

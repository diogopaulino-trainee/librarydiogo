<x-app-layout>
    <x-slot name="header">
        <h2>{{ ('Create Book') }}</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-8 p-6 bg-white shadow-md rounded-lg border border-blue-200 text-lg">
        <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- ISBN -->
            <div class="form-control">
                <label for="isbn" class="label text-blue-500 font-semibold">ISBN</label>
                <input type="text" name="isbn" id="isbn" value="{{ old('isbn') }}"
                    class="w-full p-3 rounded-md bg-blue-900 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 
                    @error('isbn') border-red-500 @enderror"
                    placeholder="Enter ISBN">
                @error('isbn')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Title -->
            <div class="form-control">
                <label for="title" class="label text-blue-500 font-semibold">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                    class="w-full p-3 rounded-md bg-blue-900 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 
                    @error('title') border-red-500 @enderror"
                    placeholder="Enter Book Name">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bibliography -->
            <div class="form-control">
                <label for="bibliography" class="label text-blue-500 font-semibold">Bibliography</label>
                <textarea name="bibliography" id="bibliography"
                    class="w-full p-3 rounded-md bg-blue-900 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 
                    @error('bibliography') border-red-500 @enderror"
                    placeholder="Enter Bibliography">{{ old('bibliography') }}</textarea>
                @error('bibliography')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Cover Image -->
            <div class="form-control">
                <label for="cover_image" class="label text-blue-500 font-semibold">Cover Image</label>
                <input type="file" name="cover_image" id="cover_image"
                    class="file-input file-input-bordered w-full bg-blue-900 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 
                    @error('cover_image') border-red-500 @enderror"
                    accept="image/*">
                @error('cover_image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Price -->
            <div class="form-control">
                <label for="price" class="label text-blue-500 font-semibold">Price (€)</label>
                <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01"
                    class="w-full p-3 rounded-md bg-blue-900 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 
                    @error('price') border-red-500 @enderror"
                    placeholder="Enter Price">
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
                    <option value="" disabled selected>Select Publisher</option>
                    @foreach ($publishers as $publisher)
                        <option value="{{ $publisher->id }}" {{ old('publisher_id') == $publisher->id ? 'selected' : '' }}>
                            {{ $publisher->name }}
                        </option>
                    @endforeach
                </select>
                @error('publisher_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Authors (Multiple Selection) -->
            <div class="form-control">
                <label for="authors" class="label text-blue-500 font-semibold">Authors</label>
                <select name="authors[]" id="authors" multiple
                    class="w-full p-3 rounded-md bg-blue-900 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 
                    @error('authors') border-red-500 @enderror">
                    @foreach ($authors as $author)
                        <option value="{{ $author->id }}" {{ (collect(old('authors'))->contains($author->id)) ? 'selected' : '' }}>
                            {{ $author->name }}
                        </option>
                    @endforeach
                </select>
                @error('authors')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit"
                    class="w-full md:w-auto px-6 py-3 bg-blue-500 text-white font-bold rounded-md hover:bg-blue-400 transition duration-300">
                    Create Book
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

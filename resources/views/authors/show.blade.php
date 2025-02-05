<x-app-layout>
    <x-slot name="header">
        <h2>{{ ('Author Details') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6 border border-blue-500">
                <div class="mb-4 text-center">
                    <img src="{{ asset('images/' . $author->photo) }}" alt="Author Photo" class="w-48 h-48 mx-auto object-cover rounded-full shadow-md">
                </div>

                <div class="grid grid-cols-1 gap-4 text-gray-800">
                    <div><strong>Name:</strong> {{ $author->name }}</div>
                    <div class="text-sm italic text-gray-600">
                        <strong>Added By:</strong> {{ $author->user->name }}
                    </div>
                </div>

                <div class="mt-6 flex justify-between">
                    <a href="{{ route('authors.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Back to List</a>
                    <a href="{{ route('authors.edit', $author) }}" class="bg-yellow-400 text-white px-4 py-2 rounded hover:bg-yellow-600">Edit Author</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

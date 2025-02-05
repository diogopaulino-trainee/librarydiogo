<x-app-layout>
    <x-slot name="header">
        @if (session('success'))
            <div class="max-w-4xl mx-auto mt-4">
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
        <h2>{{ ('List of Books') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end mb-4">
                <a href="{{ route('books.create') }}" class="btn btn-primary bg-blue-500 text-white hover:bg-blue-700 transition duration-300 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create Book
                </a>
            </div>

            <div class="max-full mx-auto mt-8 p-8 bg-white shadow-md rounded-lg border border-blue-500">
                <table class="min-w-full bg-white border border-blue-500 shadow-md rounded-lg">
                    <thead class="bg-blue-600 text-white">
                        <tr>
                            <th class="px-4 py-2 border-b">ISBN</th>
                            <th class="px-4 py-2 border-b">Title</th>
                            <th class="px-4 py-2 border-b">Author</th>
                            <th class="px-4 py-2 border-b">Publisher</th>
                            <th class="px-4 py-2 border-b text-center whitespace-nowrap">Price (€)</th>
                            <th class="px-4 py-2 border-b text-center whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($books as $book)
                        <tr class="hover:bg-blue-500 hover:text-white group">
                            <td class="px-4 py-2 border-b">{{ $book->isbn }}</td>
                            <td class="px-4 py-2 border-b">{{ $book->title }}</td>
                            <td class="px-4 py-2 border-b">{{ $book->author->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2 border-b">{{ $book->publisher->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2 border-b text-center whitespace-nowrap">{{ number_format($book->price, 2, ',', '.') }} €</td>
                            <td class="px-4 py-2 border-b text-center whitespace-nowrap space-x-2">
                                <a href="{{ route('books.show', $book) }}" title="View more details" 
                                   class="text-blue-500 group-hover:text-white transition duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0c0 3.866-4.03 7-9 7s-9-3.134-9-7 4.03-7 9-7 9 3.134 9 7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('books.edit', $book) }}" class="text-yellow-400 hover:text-yellow-800">Edit</a>
                                <a href="{{ route('books.delete', $book) }}" class="text-red-600 hover:text-red-800">Delete</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2>{{ __('Search & Add Books') }}</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-8 p-6 bg-white shadow-md rounded-lg border border-blue-200 text-lg">
        <form id="searchForm" action="{{ route('admin.books.search.results') }}" method="GET" class="space-y-6">
            <div class="form-control">
                <label for="query" class="label text-blue-500 font-semibold">Search Books</label>
                <input type="text" name="query" id="query" value="{{ request('query') }}"
                    class="w-full p-3 rounded-md bg-blue-900 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter book title, author, or ISBN">
                @error('query')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="btn bg-blue-600 text-lg text-white hover:bg-blue-700 px-4 py-2 rounded-md flex items-center shadow-md min-w-[120px] mx-1 mt-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Search
                </button>
                <button type="button" onclick="clearSearch()" class="btn bg-red-500 text-white text-lg hover:bg-red-600 px-4 py-2 rounded-md flex items-center shadow-md min-w-[120px] mx-1 mt-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Clear
                </button>
            </div>
        </form>
    </div>
    @if(isset($books) && is_array($books) && count($books) > 0)
        <div class="max-w-4xl mx-auto mt-8 p-6 bg-white shadow-md rounded-lg border border-blue-200 text-lg">
            @if (session('success'))
                <div class="max-w-4xl mx-auto mt-2 mb-6 text-lg">
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
                <div class="max-w-4xl mx-auto mt-2 mb-6 text-lg">
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
            <h3 class="text-blue-500 font-bold mb-4">Search Results</h3>

            @php
                $existingBooks = \App\Models\Book::pluck('isbn')->toArray();
            @endphp

            @foreach($books as $book)
                @php
                    $alreadyExists = !empty($book['isbn']) 
                        ? \App\Models\Book::where('isbn', $book['isbn'])->exists()
                        : \App\Models\Book::where('title', $book['title'])
                            ->whereHas('authors', function ($query) use ($book) {
                                $query->where('name', $book['authors'][0] ?? 'Unknown Author');
                            })->exists();
                @endphp

                <div class="flex items-center border-b border-gray-300 py-4">
                    <div class="w-24 h-32 flex-shrink-0">
                        <img src="{{ $book['cover'] }}" class="w-full h-full object-cover rounded-lg" alt="Book Cover">
                    </div>

                    <div class="ml-4 flex-1">
                        <h4 class="text-lg font-bold text-blue-600">{{ $book['title'] }}</h4>
                        <p class="text-sm text-gray-700"><strong>Author(s):</strong> {{ implode(', ', $book['authors']) }}</p>
                        <p class="text-sm text-gray-700"><strong>ISBN:</strong> {{ $book['isbn'] }}</p>
                        <p class="text-sm text-gray-700"><strong>Publisher:</strong> {{ $book['publisher'] }}</p>

                        <form action="{{ route('admin.books.store') }}" method="POST" class="mt-2">
                            @csrf
                            <input type="hidden" name="book" value="{{ json_encode($book) }}">

                            @if($alreadyExists)
                                <button type="button" disabled
                                    class="px-4 py-2 bg-gray-400 text-white font-bold rounded-md cursor-not-allowed">
                                    Already Added
                                </button>
                            @else
                                <button type="button" onclick="openModal({{ json_encode($book) }})"
                                    class="px-4 py-2 bg-green-500 text-white font-bold rounded-md hover:bg-green-400 transition duration-300">
                                    Add to Library
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            @endforeach
            <div class="mt-8">
                <a href="{{ route('admin.dashboard') }}" class="bg-blue-500 text-white px-4 py-3 text-lg rounded hover:bg-blue-700">Back to Dashboard</a>
            </div>
        </div>
        @else
            <div class="max-w-4xl mx-auto mt-8 p-6 bg-white shadow-md rounded-lg border border-blue-200 text-lg">
                @if (session('success'))
                    <div class="max-w-4xl mx-auto mt-2 mb-6 text-lg">
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
                    <div class="max-w-4xl mx-auto mt-2 mb-6 text-lg">
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
                <h3 class="text-blue-500 font-bold mb-4">Suggestions</h3>
                <p class="text-gray-600 mb-4">Here are some books you might be interested in:</p>
                @foreach($suggestions as $book)
                    @php
                        $alreadyExists = !empty($book['isbn']) 
                            ? \App\Models\Book::where('isbn', $book['isbn'])->exists()
                            : \App\Models\Book::where('title', $book['title'])
                                ->whereHas('authors', function ($query) use ($book) {
                                    $query->where('name', $book['authors'][0] ?? 'Unknown Author');
                                })->exists();
                    @endphp
                    <div class="flex items-center border-b border-gray-300 py-4">
                        <div class="w-24 h-32 flex-shrink-0">
                            <img src="{{ $book['cover'] }}" class="w-full h-full object-cover rounded-lg" alt="Book Cover">
                        </div>
                        <div class="ml-4 flex-1">
                            <h4 class="text-lg font-bold text-blue-600">{{ $book['title'] }}</h4>
                            <p class="text-sm text-gray-700"><strong>Author(s):</strong> {{ implode(', ', $book['authors']) }}</p>
                            <p class="text-sm text-gray-700"><strong>ISBN:</strong> {{ $book['isbn'] ?? 'Added via external API' }}</p>
                            <p class="text-sm text-gray-700"><strong>Publisher:</strong> {{ $book['publisher'] }}</p>
                
                            <form action="{{ route('admin.books.store') }}" method="POST" class="mt-2">
                                @csrf
                                <input type="hidden" name="book" value="{{ json_encode($book) }}">
                
                                @if($alreadyExists)
                                    <button type="button" disabled
                                        class="px-4 py-2 bg-gray-400 text-white font-bold rounded-md cursor-not-allowed">
                                        Already Added
                                    </button>
                                @else
                                    <button type="button" onclick="openModal({{ json_encode($book) }})"
                                        class="px-4 py-2 bg-green-500 text-white font-bold rounded-md hover:bg-green-400 transition duration-300">
                                        Add to Library
                                    </button>
                                @endif
                            </form>
                        </div>
                    </div>
                @endforeach
                <div class="mt-8">
                    <a href="{{ route('admin.dashboard') }}" class="bg-blue-500 text-white px-4 py-3 text-lg rounded hover:bg-blue-700">Back to Dashboard</a>
                </div>
            </div>
        @endif

    <div id="add-book-modal" class="fixed inset-0 hidden items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="bg-white rounded-lg shadow-md border border-blue-200 max-w-sm w-full p-6 relative">
            <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
    
            <div class="text-xl font-semibold text-blue-700 flex items-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11V5a1 1 0 10-2 0v2a1 1 0 102 0zm0 6a1 1 0 11-2 0v-4a1 1 0 112 0v4z" clip-rule="evenodd" />
                </svg>
                Confirm Book Addition
            </div>
    
            <div class="text-lg text-gray-600">
                <p><strong>Book:</strong> <span id="modal-book-title"></span></p>
                <p><strong>Are you sure you want to add this book to the library?</strong></p>
            </div>
    
            <div class="flex justify-end mt-4 text-lg">
                <form id="confirmForm" action="{{ route('admin.books.store') }}" method="POST" onsubmit="closeModal()">
                    @csrf
                    <input type="hidden" name="book" id="bookData">
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 mr-2 rounded-md hover:bg-green-700">
                        Confirm
                    </button>
                </form>
                <button onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                    Cancel
                </button>
            </div>
        </div>
    </div>
    
    <script>
        function openModal(book) {
            document.getElementById('modal-book-title').textContent = book.title;
            document.getElementById('bookData').value = JSON.stringify(book);
            console.log(document.getElementById('bookData').value);
            const modal = document.getElementById('add-book-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('add-book-modal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        function clearSearch() {
            const form = document.getElementById('searchForm');

            const searchInput = form.querySelector('[name="query"]');
            if (searchInput) searchInput.value = '';

            window.location.href = form.getAttribute('action');
        }
    </script>
</x-app-layout>

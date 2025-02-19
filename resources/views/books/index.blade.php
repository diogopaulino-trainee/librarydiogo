<x-app-layout>
    <x-slot name="header">
        <h2>{{ ('List of Books') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(auth()->user() && auth()->user()->hasRole('Admin'))
            <div class="flex justify-end mb-4">
                <a href="{{ route('books.create') }}" class="btn btn-primary bg-blue-500 text-lg text-white hover:bg-blue-700 transition duration-300 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create Book
                </a>
            </div>
            @endif

            <div class="max-full mx-auto mt-8 p-8 bg-white shadow-md rounded-lg border border-blue-500">
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
                <div class="flex flex-col md:flex-row justify-between items-center mb-4 w-full">
                    <form id="searchForm" action="{{ route('books.index') }}" method="GET" class="flex items-center w-full">
                        <div class="flex flex-wrap w-full">
                            <select name="availability" onchange="this.form.submit()"
                                class="border border-blue-500 text-blue-600 bg-white px-8 py-2 rounded-md shadow-sm focus:ring-blue-400 focus:border-blue-400 transition duration-200 ease-in-out text-lg mx-1 mt-1">
                                <option value="">All Books</option>
                                <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="unavailable" {{ request('availability') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                            </select>
                
                            <div class="relative flex-grow max-w-[400px] mr-2">
                                <input type="text" name="search" placeholder="Search by ISBN, Title, Author, or Publisher" 
                                    value="{{ request('search') }}" 
                                    class="border border-blue-500 px-4 py-2 focus:ring-blue-400 focus:border-blue-400 transition duration-200 ease-in-out w-full pl-10 pr-4 rounded-md shadow-sm text-lg mx-1 mt-1"/>
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>
                
                            <button type="submit" class="btn bg-blue-600 text-lg text-white hover:bg-blue-700 px-4 py-2 rounded-md flex items-center shadow-md min-w-[120px] mx-1 mt-1">
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
                
                        <div class="w-auto">
                            <select name="filter" onchange="this.form.submit()" 
                                class="border border-blue-500 text-lg rounded-md px-4 py-2 bg-blue-500 text-white shadow-md focus:outline-none h-12 ml-2 mx-1 mt-1">
                                <option value="" {{ request('filter') == '' ? 'selected' : '' }}>All</option>
                                <option value="price_asc" {{ request('filter') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_desc" {{ request('filter') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="az" {{ request('filter') == 'az' ? 'selected' : '' }}>Title: A to Z</option>
                                <option value="za" {{ request('filter') == 'za' ? 'selected' : '' }}>Title: Z to A</option>
                            </select>
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white text-lg border border-blue-500 shadow-md rounded-lg">
                        <thead class="bg-blue-600 text-white">
                        <tr>
                            @auth
                                @if(auth()->user()->hasRole('Citizen'))
                                    <th class="px-4 py-2 border-b">Favorite</th>
                                    <th class="px-4 py-2 border-b">Availability</th>
                                @endif
                                @if(auth()->user()->hasRole('Admin'))
                                    <th class="px-4 py-2 border-b">Availability</th>
                                @endif
                            @endauth
                            <th class="px-4 py-2 border-b">ISBN</th>
                            <th class="px-4 py-2 border-b">Title</th>
                            <th class="px-4 py-2 border-b">Author</th>
                            <th class="px-4 py-2 border-b">Publisher</th>
                            <th class="px-4 py-2 border-b text-center">Price&nbsp;(€)</th>
                            <th class="px-4 py-2 border-b text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($books as $book)
                        <tr class="hover:bg-blue-500 hover:text-white group">
                            @auth
                                @if(auth()->user()->hasRole('Citizen'))
                                    <td class="px-4 py-2 text-center">
                                        <button id="favorite-btn-{{ $book->id }}" 
                                                class="p-2 rounded-full transition duration-300"
                                                onclick="toggleFavorite({{ $book->id }})">
                                            <svg id="favorite-icon-{{ $book->id }}" 
                                                xmlns="http://www.w3.org/2000/svg" 
                                                class="h-9 w-9 transition duration-300 hover:fill-red-500"
                                                viewBox="0 0 24 24" 
                                                fill="{{ auth()->user()->favorites->contains($book->id) ? 'red' : 'none' }}" 
                                                stroke="red" stroke-width="1">
                                                <path stroke-linecap="round" stroke-linejoin="round" 
                                                    d="M12 21l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.18L12 21z" />
                                            </svg>
                                        </button>
                                    </td>
                                @endif
                            @endauth

                            @auth
                                @if(auth()->user()->hasRole('Citizen'))
                                    <td class="px-4 py-2">
                                        @if($book->status === 'available')
                                            <span class="text-green-500 font-semibold flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Available
                                            </span>
                                            <button onclick="openRequestModal({{ $book->id }})" class="btn bg-blue-500 text-lg text-white px-4 py-2 rounded hover:bg-blue-700">
                                                Request
                                            </button>
                                        @else
                                            <span class="text-gray-500 group-hover:text-white">Not available</span>
                                        @endif
                                    </td>
                                @endif
                            @endauth

                            @auth
                                @if(auth()->user()->hasRole('Admin'))
                                    <td class="px-4 py-2">
                                        <span class="{{ $book->status === 'available' ? 'text-green-500' : 'text-red-600' }}">
                                            {{ ucfirst($book->status) }}
                                        </span>
                                    </td>
                                @endif
                            @endauth

                            <td class="px-4 py-2">{{ $book->isbn ?? 'Added via external API' }}</td>
                            <td class="px-4 py-2">
                                @if($book)
                                    <a href="{{ route('books.show', $book->id) }}" 
                                       class="hover:underline group-hover:text-white">
                                        {{ $book->title }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                @forelse ($book->authors as $author)
                                    {{ $author->name }}{{ !$loop->last ? ', ' : '' }}
                                @empty
                                    N/A
                                @endforelse
                            </td>
                            <td class="px-4 py-2">{{ $book->publisher->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2 text-center">{{ number_format($book->price, 2, ',', '.') }}&nbsp;€</td>                            
                            <td class="px-4 py-2 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('books.show', $book) }}" title="View Details" class="text-blue-500 group-hover:text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0c0 3.866-4.03 7-9 7s-9-3.134-9-7 4.03-7 9-7 9 3.134 9 7z" />
                                        </svg>
                                    </a>
                                    <button onclick="openTimestampsModal({{ $book->id }})"
                                            class="relative text-gray-700 hover:text-blue-600 transition duration-200 group flex items-center justify-center p-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-6.32-8.59"/>
                                        </svg>
                                        <span class="absolute bottom-full mb-2 hidden group-hover:flex items-center 
                                                    bg-white text-black text-xs font-semibold px-2 py-1 rounded-md shadow-md
                                                    border border-gray-400 z-50">
                                            View Timestamps
                                        </span>
                                    </button>
                                    @auth
                                        @if(auth()->user()->hasRole('Admin'))
                                            <a href="{{ route('books.edit', $book) }}" class="text-yellow-400 font-semibold hover:text-white transition duration-200">Edit</a>
                                            <a href="{{ route('books.delete', $book) }}" class="text-red-500 font-semibold hover:text-white transition duration-200">Delete</a>
                                        @endif
                                    @endauth
                                </div>
                            </td>
                        </tr>

                        <div id="request-modal-{{ $book->id }}" class="fixed inset-0 bg-blue-900 bg-opacity-55 hidden items-center justify-center z-50">
                            <div class="bg-white rounded-lg shadow-md border border-blue-200 max-w-sm w-full p-6 relative">
                                <button onclick="closeModal('request-modal-{{ $book->id }}')" class="absolute top-2 right-2 text-gray-500 hover:text-red-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                                <div class="text-xl font-semibold text-blue-700 flex items-center mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11V5a1 1 0 10-2 0v2a1 1 0 102 0zm0 6a1 1 0 11-2 0v-4a1 1 0 112 0v4z" clip-rule="evenodd" />
                                    </svg>
                                    Confirm Request
                                </div>

                                <div class="text-base text-gray-600">
                                    <p><strong>Book:</strong> {{ $book->title }}</p>
                                    <p><strong>Are you sure you want to request this book?</strong></p>
                                </div>

                                <div class="flex justify-end mt-4 text-lg">
                                    <form id="requestForm-{{ $book->id }}" action="{{ route('requests.store', $book) }}" method="POST" class="hidden">
                                        @csrf
                                        <button type="submit" id="submitRequest" class="bg-blue-500 text-white px-4 py-2 mr-2 rounded-md hover:bg-blue-700">
                                            Confirm
                                        </button>
                                    </form>
                                    <button onclick="closeModal('request-modal-{{ $book->id }}')" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div id="timestamps-modal-{{ $book->id }}" class="fixed inset-0 bg-blue-900 bg-opacity-55 hidden items-center justify-center z-50">
                            <div class="bg-white rounded-lg shadow-md border border-blue-200 max-w-sm w-full p-6 relative">
                                <button onclick="closeModal('timestamps-modal-{{ $book->id }}')" class="absolute top-2 right-2 text-gray-500 hover:text-red-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                                <div class="text-xl font-semibold text-blue-700 flex items-center mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11V5a1 1 0 10-2 0v2a1 1 0 102 0zm0 6a1 1 0 11-2 0v-4a1 1 0 112 0v4z" clip-rule="evenodd" />
                                    </svg>
                                    Timestamps
                                </div>

                                <div class="text-base text-gray-600">
                                    <p><strong>Created At:</strong> {{ $book->created_at->format('d/m/Y H:i') }}</p>
                                    <p><strong>Updated At:</strong> {{ $book->updated_at->format('d/m/Y H:i') }}</p>
                                </div>

                                <div class="flex justify-end mt-4">
                                    <button onclick="closeModal('timestamps-modal-{{ $book->id }}')" class="bg-blue-500 text-lg text-white px-4 py-2 rounded-md hover:bg-blue-700">Close</button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
                </div>

                <div class="mt-4">
                    {{ $books->appends(request()->query())->links() }}
                </div>
                
                <div x-data="{ open: false }" class="relative flex justify-end mt-6">
                    <button @click="open = !open" 
                            class="btn bg-green-500 text-lg text-white hover:bg-green-700 transition duration-300 shadow-md flex items-center px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M12 3v12m0 0l-4-4m4 4l4-4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M5 20h14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Export
                    </button>
                
                    <div x-show="open" @click.away="open = false" 
                         x-transition.origin-bottom
                         class="absolute bottom-14 right-0 w-40 bg-white border border-gray-300 shadow-lg rounded-md z-10">
                        <a href="{{ route('books.export', ['format' => 'excel']) }}" 
                           class="px-4 py-2 text-gray-800 hover:bg-green-100 transition flex items-center text-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-2 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M4 4h16v16H4z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M8 4v16M16 4v16M4 8h16M4 16h16M4 12h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Export to Excel
                        </a>
                        <a href="{{ route('books.export', ['format' => 'pdf']) }}" 
                           class="px-4 py-2 text-gray-800 hover:bg-red-100 transition flex items-center text-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-2 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M6 2h12l4 4v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M14 2v4h4M9 11h6M9 15h3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Export to PDF
                        </a>
                    </div>
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
                let icon = document.getElementById(`favorite-icon-${bookId}`);
                icon.setAttribute("fill", data.favorited ? "red" : "none");
            })
            .catch(error => console.error('Erro ao marcar/desmarcar favorito:', error));
        }
        
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.getElementById(id).classList.add('flex');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.getElementById(id).classList.remove('flex');
        }

        function openRequestModal(bookId) {
            document.getElementById(`request-modal-${bookId}`).classList.remove('hidden');
            document.getElementById(`request-modal-${bookId}`).classList.add('flex');
        }

        function confirmRequest(bookId) {
            closeModal(`request-modal-${bookId}`);
        }

        function openTimestampsModal(bookId) {
            document.getElementById(`timestamps-modal-${bookId}`).classList.remove('hidden');
            document.getElementById(`timestamps-modal-${bookId}`).classList.add('flex');
        }

        function clearSearch() {
            const form = document.getElementById('searchForm');

            const searchInput = form.querySelector('[name="search"]');
            if (searchInput) searchInput.value = '';

            const availabilitySelect = form.querySelector('select[name="availability"]');
            if (availabilitySelect) availabilitySelect.value = '';

            const filterSelect = form.querySelector('select[name="filter"]');
            if (filterSelect) filterSelect.value = '';
            
            form.submit();
        }

        function submitRequestForm(bookId) {
            let form = document.getElementById(`requestForm-${bookId}`);
            form.submit();
        }
    </script>
</x-app-layout>

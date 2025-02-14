<x-app-layout>
    <x-slot name="header">
        <h2>{{ __('Book Details') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6 border border-blue-500 relative">

                @auth
                    <button id="favorite-btn" 
                        class="absolute top-3 right-3 bg-white border border-red-500 p-4 rounded-full shadow-xl transition duration-300 group"
                        onclick="toggleFavorite({{ $book->id }})">
                        <svg id="favorite-icon" xmlns="http://www.w3.org/2000/svg" 
                            class="h-8 w-8 transition duration-300 group-hover:fill-red-600" 
                            viewBox="0 0 24 24" 
                            fill="{{ auth()->user()->favorites->contains($book->id) ? 'red' : 'none' }}" 
                            stroke="red" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" 
                                d="M12 21l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.18L12 21z" />
                        </svg>
                    </button>
                @endauth

                <div class="mt-20 flex justify-between items-center">
                    @if(!empty($previousBook))
                        <a href="{{ route('books.show', $previousBook->id) }}" 
                           class="bg-red-500 text-white text-lg px-6 py-2 rounded-lg hover:bg-red-700 transition duration-300 shadow-md flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Previous
                        </a>
                    @else
                        <span class="px-6 py-2 text-gray-400 cursor-not-allowed">← Previous</span>
                    @endif

                    @if(!empty($nextBook))
                        <a href="{{ route('books.show', $nextBook->id) }}" 
                           class="bg-green-500 text-white text-lg px-6 py-2 rounded-lg hover:bg-green-700 transition duration-300 shadow-md flex items-center">
                            Next
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @else
                        <span class="px-6 py-2 text-gray-400 cursor-not-allowed">Next →</span>
                    @endif
                </div>

                <div class="mb-4 text-center">
                    <img src="{{ asset(str_starts_with($book->cover_image, 'images/') ? $book->cover_image : 'images/' . $book->cover_image) }}" 
                         alt="Cover Image" 
                         class="w-48 h-64 mx-auto object-cover rounded-lg shadow-md">
                </div>

                <div class="grid grid-cols-1 gap-4 text-gray-800 text-lg">
                    <div><strong>ISBN:</strong> {{ $book->isbn }}</div>
                    <div><strong>Title:</strong> {{ $book->title }}</div>
                    <div>
                        <strong>Authors:</strong> 
                        @if($book->authors->isNotEmpty())
                            @foreach ($book->authors as $author)
                                <a href="{{ route('authors.show', $author->id) }}" 
                                   class="text-blue-600 font-semibold hover:underline">
                                    {{ $author->name }}
                                </a>{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        @else
                            N/A
                        @endif
                    </div>
                    <div>
                        <strong>Publisher:</strong> 
                        @if($book->publisher)
                            <a href="{{ route('publishers.show', $book->publisher->id) }}" 
                               class="text-blue-600 font-semibold hover:underline">
                                {{ $book->publisher->name }}
                            </a>
                        @else
                            N/A
                        @endif
                    </div>
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

                <div class="mt-6">
                    @if(!$pendingRequest) 
                    @if(auth()->check() && auth()->user()->hasRole('Citizen'))
                            <form action="{{ route('requests.store', $book) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700 w-full">
                                    Request This Book
                                </button>
                            </form>
                        @elseif(auth()->check() && auth()->user()->hasRole('Admin') && $citizens->isNotEmpty())
                        <form action="{{ route('requests.store.admin', $book) }}" method="POST">
                            @csrf
                            <label for="citizen_id" class="block text-lg font-medium text-gray-700">Select Citizen:</label>
                            <select name="citizen_id" id="citizen_id" required class="mt-1 block w-full text-lg p-2 border border-gray-300 rounded-md">
                                <option value="" disabled selected>Choose a Citizen</option>
                                @foreach($citizens as $citizen)
                                    @php
                                        $textColor = '';
                                        if ($citizen->requests_left == 0 || $citizen->requests_left == 1) {
                                            $textColor = 'text-red-500';
                                        } elseif ($citizen->requests_left == 2) {
                                            $textColor = 'text-orange-500';
                                        } elseif ($citizen->requests_left == 3) {
                                            $textColor = 'text-green-500';
                                        }
                                    @endphp
                                    <option value="{{ $citizen->id }}" class="{{ $textColor }}">
                                        {{ $citizen->name }} ({{ $citizen->requests_left }} request{{ $citizen->requests_left > 1 ? 's' : '' }} remaining)
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="bg-green-500 text-white text-lg font-bold px-4 py-2 rounded hover:bg-green-700 w-full mt-2">
                                Request for Citizen
                            </button>
                        </form>
                        @elseif(auth()->check())
                            <p class="text-gray-500 italic">No citizens available for selection.</p>
                        @else
                            <p class="text-gray-500 italic">
                                Please <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-700">log in</a> to make a request.
                            </p>
                        @endif
                    @else
                        <p class="text-red-500 text-lg font-bold mt-4">
                            This book is currently unavailable.
                            <br>
                            <span class="text-gray-700 text-lg font-medium">
                                Expected to be available by: 
                                <strong>
                                    {{ \Carbon\Carbon::parse($pendingRequest->expected_return_date)->format('d M, Y') }}
                                </strong>
                            </span>
                        </p>
                    @endif
                </div>

                @if (session('success'))
                    <div class="max-w-4xl mx-auto mt-2 mb-4">
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
                    <div class="max-w-4xl mx-auto mt-2 mb-4">
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

                <div class="mt-6 flex justify-between">
                    <a href="{{ route('books.index') }}" class="bg-blue-500 text-white text-lg px-4 py-2 rounded hover:bg-blue-700">Back to List</a>
                    
                    @auth
                        @if(auth()->user()->hasRole('Admin'))
                            <a href="{{ route('books.edit', $book) }}" class="bg-yellow-400 text-white text-lg px-4 py-2 rounded hover:bg-yellow-600">Edit Book</a>
                        @endif
                    @endauth
                </div>

                @if(auth()->user()->hasRole('Admin'))
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-800">Request History</h3>
                
                    @if($requests->isEmpty())
                        <p class="text-gray-500">No requests have been made for this book yet.</p>
                    @else
                        <table class="min-w-full text-lg table-auto mt-4 border border-gray-300 rounded-lg shadow-md">
                            <thead class="bg-blue-600 text-white">
                                <tr>
                                    <th class="px-4 py-2 border-b text-center">Request Number</th>
                                    <th class="px-4 py-2 text-left">Request Date</th>
                                    <th class="px-4 py-2 text-left">Returned Date</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                    <th class="px-4 py-2 text-left">User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests as $request)
                                    <tr class="border-b">
                                        <td class="px-4 py-2 text-center">{{ $request->request_number }}</td>
                                        <td class="px-4 py-2 text-left">{{ \Carbon\Carbon::parse($request->created_at)->format('d M, Y') }}</td>
                                        <td class="px-4 py-2 text-left">
                                            @if($request->actual_return_date)
                                                {{ \Carbon\Carbon::parse($request->actual_return_date)->format('d M, Y') }}
                                            @else
                                                <span class="text-red-500">Not returned</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 text-left">
                                            @if($request->actual_return_date)
                                                <span class="text-green-600">Returned</span>
                                            @else
                                                <span class="text-red-600">Pending</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 text-left">{{ $request->user->name ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                @endif
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

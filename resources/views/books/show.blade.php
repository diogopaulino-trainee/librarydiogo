<x-app-layout>
    <x-slot name="header">
        <h2>{{ __('Book Details') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6 border border-blue-500 relative">
                @if (session('success'))
                    <div class="max-w-4xl mx-auto mt-12 mb-2">
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
                    <div class="max-w-4xl mx-auto mt-12 mb-2">
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
                @if ($errors->any())
                    <div class="max-w-4xl mx-auto mt-12 mb-2">
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-md" role="alert">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-red-700 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <strong class="font-bold text-red-800">Validation Error</strong>
                            </div>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button onclick="this.parentElement.style.display='none'" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg class="fill-current h-6 w-6 text-red-700" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <title>Close</title>
                                    <path d="M14.348 5.652a1 1 0 00-1.414 0L10 8.586 7.066 5.652a1 1 0 10-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 11.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934a1 1 0 000-1.414z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                @auth
                    <button id="favorite-btn" 
                        class="absolute top-3 right-3 bg-white border border-red-500 p-4 rounded-full shadow-xl transition duration-300 group"
                        onclick="toggleFavorite({{ $book->id }})">
                        <svg id="favorite-icon" xmlns="http://www.w3.org/2000/svg" 
                            class="h-10 w-10 transition duration-300 group-hover:fill-red-600" 
                            viewBox="0 0 24 24" 
                            fill="{{ auth()->user()->favorites->contains($book->id) ? 'red' : 'none' }}" 
                            stroke="red" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" 
                                d="M12 21l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.18L12 21z" />
                        </svg>
                    </button>
                @endauth

                <div class="mt-20 flex justify-between items-center my-2">
                    @if(!empty($previousBook))
                        <a href="{{ route('books.show', $previousBook->id) }}" 
                           class="bg-red-500 text-white text-lg px-6 py-2 rounded-lg hover:bg-red-700 transition duration-300 shadow-md flex items-center justify-center min-w-[150px]">
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
                           class="bg-green-500 text-white text-lg px-6 py-2 rounded-lg hover:bg-green-700 transition duration-300 shadow-md flex items-center justify-center min-w-[150px]">
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
                    <div><strong>ISBN:</strong> {{ $book->isbn ?? 'Added via external API' }}</div>
                    <div><strong>Title:</strong> <span id="bookTitle">{{ $book->title }}</span></div>
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
                    <div><strong>Price:</strong> <span id="bookPrice">{{ number_format($book->price, 2, ',', '.') }} €</span></div>
                    <div class="text-sm italic text-gray-600">
                        <strong>Added By:</strong> {{ $book->user->name }}
                    </div>
                </div>

                <div class="mt-6">
                    @if(!$borrowedRequest && $book->status !== 'unavailable')
                        @if(auth()->check() && auth()->user()->hasRole('Citizen'))
                            <form action="{{ route('requests.store', $book) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-lg font-semibold px-6 py-3 rounded-lg shadow-md flex items-center justify-center transition w-full 
                                bg-green-500 hover:bg-green-700 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 19.5V6a2 2 0 0 1 2-2h6v14H6a2 2 0 0 0-2 1.5z" />
                                    <path d="M20 19.5V6a2 2 0 0 0-2-2h-6v14h6a2 2 0 0 1 2 1.5z" />
                                </svg>
                                
                                Request This Book
                            </button>
                            </form>
                        @elseif(auth()->check() && auth()->user()->hasRole('Admin') && $citizens->isNotEmpty())
                            <form action="{{ route('requests.store.admin', $book) }}" method="POST">
                                @csrf
                                <label for="citizen_id" class="block text-lg font-medium text-gray-700">Select Citizen:</label>
                                <select name="citizen_id" id="citizen_id" class="mt-1 block w-full text-lg p-2 border border-gray-300 rounded-md">
                                    <option value="" disabled selected>Choose a Citizen</option>
                                    @foreach($citizens as $citizen)
                                        @php
                                            $textColor = $citizen->requests_left == 0 || $citizen->requests_left == 1 ? 'text-red-500' : 
                                                         ($citizen->requests_left == 2 ? 'text-orange-500' : 'text-green-500');
                                        @endphp
                                        <option value="{{ $citizen->id }}" class="{{ $textColor }}">
                                            {{ $citizen->name }} ({{ $citizen->requests_left }} request{{ $citizen->requests_left > 1 ? 's' : '' }} remaining)
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="text-lg font-semibold px-6 py-3 rounded-lg shadow-md flex items-center justify-center transition w-full 
                                bg-green-500 hover:bg-green-700 text-white mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="8" r="4" />
                                    <path d="M6 20a6 6 0 0112 0" />
                                </svg>
                                    Request for Citizen
                                </button>
                            </form>
                        @elseif(auth()->check())
                            <p class="text-gray-500 italic">No citizens available for selection.</p>
                        @endif
                
                        @if(auth()->check() && auth()->user()->hasRole('Citizen') && $book->status === 'available')
                        @php
                            $isInCart = auth()->user()->cartItems()->where('book_id', $book->id)->exists();
                        @endphp
                        <div class="mt-6">
                            <form id="addToCartForm" action="{{ route('cart.add', $book->id) }}" method="POST">
                                @csrf
                                <p class="text-gray-700 mb-2">
                                    This book is also available for purchase. Click below to add it to your cart.
                                </p>
                                <button type="submit" id="addToCartBtn"
                                    class="text-lg font-semibold px-6 py-3 rounded-lg shadow-md flex items-center justify-center transition w-full
                                        {{ $isInCart ? 'bg-gray-400 text-gray-700 cursor-not-allowed' : 'bg-orange-500 hover:bg-orange-700 text-white' }}" 
                                    {{ $isInCart ? 'disabled' : '' }}>

                                    <span id="loadingSpinner" class="hidden animate-spin mr-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 2v20m8-8H4"></path>
                                        </svg>
                                    </span>

                                    <svg id="cartIcon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 3h2l3 10h11l3-7H6"></path>
                                        <circle cx="9" cy="20" r="2"></circle>
                                        <circle cx="18" cy="20" r="2"></circle>
                                    </svg>

                                    <span id="btnText">{{ $isInCart ? 'Already in Cart' : 'Add to Cart' }}</span>
                                </button>
                            </form>
                        </div>
                        @endif
                
                        @if(!auth()->check())
                            <p class="text-gray-500 italic">
                                Please <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-700">log in</a> to request or purchase this book.
                            </p>
                        @elseif($borrowedRequest && $borrowedRequest->expected_return_date)
                            <p class="text-red-500 text-lg font-bold mt-4">
                                This book is currently unavailable.
                                <br>
                                <span class="text-gray-700 text-lg font-medium">
                                    Expected to be available by: 
                                    <strong>
                                        {{ \Carbon\Carbon::parse($borrowedRequest->expected_return_date)->format('d M, Y') }}
                                    </strong>
                                </span>
                            </p>
                        @endif
                
                        @if(auth()->check() && auth()->user()->hasRole('Citizen'))
                            @php
                                $alreadySubscribed = auth()->user()->isSubscribedToNotification($book->id);
                                $hasBorrowedBook = $borrowedRequest && $borrowedRequest->user_id == auth()->id();
                            @endphp

                            @if($hasBorrowedBook)
                                <button class="mt-4 bg-gray-400 cursor-not-allowed text-white px-4 py-2 rounded w-full" disabled>
                                    You currently have this book
                                </button>
                            @elseif($book->status === 'unavailable')
                                @if($alreadySubscribed)
                                    <form action="{{ route('books.cancel_notify', $book) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="mt-4 bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded w-full">
                                            Cancel Notification
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('books.notify', $book) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded w-full">
                                            Notify me when available
                                        </button>
                                    </form>
                                @endif
                            @endif
                        @endif
                    @endif
                </div>

                <div class="mt-8">
                    @if ($relatedBooks['authors']->isNotEmpty() || $relatedBooks['publishers']->isNotEmpty() || $relatedBooks['similar']->isNotEmpty())
                        <h3 class="text-2xl font-semibold text-gray-800 mb-4">Related Books</h3>

                        @if ($relatedBooks['similar']->isNotEmpty())
                            <h4 class="text-xl font-semibold text-gray-700 mt-6 mb-4">With similar Bibliography</h4>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach ($relatedBooks['similar'] as $related)
                                    <div class="bg-white shadow-md p-4 rounded-lg" style="transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;" 
                                    onmouseover="this.style.transform='scale(1.03)'; this.style.boxShadow='0 4px 10px rgba(0, 0, 0, 0.15)';" 
                                    onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 2px 5px rgba(0, 0, 0, 0.1)';">
                                        <a href="{{ route('books.show', $related->id) }}">
                                            <img src="{{ asset('images/' . $related->cover_image) }}" alt="Cover" class="w-full h-48 object-cover rounded-lg">
                                            <h4 class="text-lg font-semibold mt-2">{{ $related->title }}</h4>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if ($relatedBooks['authors']->isNotEmpty())
                            <h4 class="text-xl font-semibold text-gray-700 mt-6 mb-4">By the same Author(s)</h4>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach ($relatedBooks['authors'] as $related)
                                    <div class="bg-white shadow-md p-4 rounded-lg" style="transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;" 
                                    onmouseover="this.style.transform='scale(1.03)'; this.style.boxShadow='0 4px 10px rgba(0, 0, 0, 0.15)';" 
                                    onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 2px 5px rgba(0, 0, 0, 0.1)';">
                                        <a href="{{ route('books.show', $related->id) }}">
                                            <img src="{{ asset('images/' . $related->cover_image) }}" alt="Cover" class="w-full h-48 object-cover rounded-lg">
                                            <h4 class="text-lg font-semibold mt-2">{{ $related->title }}</h4>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if ($relatedBooks['publishers']->isNotEmpty())
                            <h4 class="text-xl font-semibold text-gray-700 mt-6 mb-4">From the same Publisher</h4>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach ($relatedBooks['publishers'] as $related)
                                    <div class="bg-white shadow-md p-4 rounded-lg" style="transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;" 
                                    onmouseover="this.style.transform='scale(1.03)'; this.style.boxShadow='0 4px 10px rgba(0, 0, 0, 0.15)';" 
                                    onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 2px 5px rgba(0, 0, 0, 0.1)';">
                                        <a href="{{ route('books.show', $related->id) }}">
                                            <img src="{{ asset('images/' . $related->cover_image) }}" alt="Cover" class="w-full h-48 object-cover rounded-lg">
                                            <h4 class="text-lg font-semibold mt-2">{{ $related->title }}</h4>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        
                    @endif
                </div>

                <div class="mt-8 flex justify-between">
                    <a href="{{ route('books.index') }}" class="bg-blue-500 text-white text-lg px-4 py-2 rounded hover:bg-blue-700">Back to List</a>
                    
                    @auth
                        @if(auth()->user()->hasRole('Admin'))
                            <a href="{{ route('books.edit', $book) }}" class="bg-yellow-400 text-white text-lg px-4 py-2 rounded hover:bg-yellow-600">Edit Book</a>
                        @endif
                    @endauth
                </div>

                @if(auth()->check() && auth()->user()->hasRole('Admin'))
                <div class="mt-6">
                    <h3 class="text-2xl font-semibold text-gray-800 mb-4">Request History</h3>
                
                    @if($requests->isEmpty())
                        <p class="text-gray-500">No requests have been made for this book yet.</p>
                    @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-lg table-auto mt-4 border border-gray-300 rounded-lg shadow-md">
                            <thead class="bg-blue-600 text-white">
                                <tr>
                                    <th class="px-4 py-2 border-b text-center">Request Number</th>
                                    <th class="px-4 py-2 text-center">Request Date</th>
                                    <th class="px-4 py-2 text-center">Returned Date</th>
                                    <th class="px-4 py-2 text-center">Status</th>
                                    <th class="px-4 py-2 text-center">User</th>
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
                                                <span class="text-red-600">Borrowed</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 text-left">{{ $request->user->name ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
                @endif

                <x-section-border />

                <div class="mt-6">
                    @if(auth()->check() && auth()->user()->hasRole('Citizen'))
                    @php
                        $hasPurchasedBook = auth()->user()->orders()
                            ->whereHas('items', function ($query) use ($book) {
                                $query->where('book_id', $book->id);
                            })->exists();
                    @endphp
                        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                            <h3 class="text-2xl font-semibold text-gray-800 border-b pb-2 mb-4">Leave a Review</h3>
                
                            @if(!$returnedRequest && !$hasPurchasedBook)
                                <div class="bg-red-100 text-red-600 p-4 rounded-md text-center">
                                    <p class="text-lg font-medium">You need to read or purchase this book before leaving a review.</p>
                                </div>
                            @elseif($userReview)
                                <div class="bg-green-100 text-green-600 p-4 rounded-md text-center">
                                    <p class="text-lg font-medium">You have already submitted a review for this book.</p>
                                </div>
                                <button class="bg-gray-400 text-white px-4 py-2 rounded mt-4 w-full cursor-not-allowed" disabled>
                                    Review Submitted
                                </button>
                            @else
                                <form action="{{ route('reviews.store') }}" method="POST" class="mt-4">
                                    @csrf
                                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                
                                    <label class="block text-gray-700 font-semibold mb-2">Rating</label>
                                    <div class="flex space-x-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="rating" value="{{ $i }}" class="hidden peer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 transition duration-200 fill-gray-300 hover:fill-yellow-400 peer-checked:fill-yellow-500"
                                                    viewBox="0 0 24 24">
                                                    <path d="M12 17.3l-6.2 3.7 1.6-6.9L2 9.2l7-.6L12 2l3 6.6 7 .6-5.4 4.9 1.6 6.9z"/>
                                                </svg>
                                            </label>
                                        @endfor
                                    </div>
                
                                    <label class="block text-gray-700 font-semibold mt-4">Your Review</label>
                                    <textarea name="comment" class="w-full border p-2 rounded-md" rows="4" placeholder="Write your review here..."></textarea>
                
                                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 transition mt-4 w-full">
                                        Submit Review
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                
                    <div class="mt-8 bg-white p-6 rounded-lg shadow-md border border-gray-200">
                        <h3 class="text-2xl font-semibold text-gray-800 border-b pb-2 mb-4">User Reviews</h3>
                
                        @if($book->reviews->isEmpty())
                            <p class="text-gray-500 text-center text-lg">No reviews yet. Be the first to review this book!</p>
                        @else
                            <div class="space-y-4">
                                @foreach($book->reviews as $review)
                                <div class="bg-gray-50 p-4 rounded-lg shadow-sm border border-gray-200" 
                                    style="transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;" 
                                    onmouseover="this.style.transform='scale(1.03)'; this.style.boxShadow='0 4px 10px rgba(0, 0, 0, 0.15)';" 
                                    onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 2px 5px rgba(0, 0, 0, 0.1)';">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xl font-semibold text-gray-800">{{ $review->user->name }}</span>
                                            <span class="ml-2 text-yellow-500">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $review->rating)
                                                        ★
                                                    @else
                                                        ☆
                                                    @endif
                                                @endfor
                                            </span>
                                        </div>
                                        <p class="text-lg text-gray-800 mt-2">{{ $review->comment }}</p>
                                        <span class="text-sm italic text-gray-500">Reviewed on {{ $review->created_at->format('d M, Y') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
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
                let icon = document.getElementById('favorite-icon');
                icon.setAttribute("fill", data.favorited ? "red" : "none");
            })
            .catch(error => console.error('Erro ao marcar/desmarcar favorito:', error));
        }

        document.querySelectorAll('input[name="rating"]').forEach(radio => {
            radio.addEventListener('change', function() {
                let selectedValue = this.value;
                document.querySelectorAll('input[name="rating"]').forEach(star => {
                    let starSVG = star.nextElementSibling;
                    if (star.value <= selectedValue) {
                        starSVG.classList.remove("fill-gray-300", "hover:fill-yellow-400");
                        starSVG.classList.add("fill-yellow-500");
                    } else {
                        starSVG.classList.remove("fill-yellow-500");
                        starSVG.classList.add("fill-gray-300");
                    }
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            const addToCartForm = document.getElementById("addToCartForm");
            const addToCartBtn = document.getElementById("addToCartBtn");
            const loadingSpinner = document.getElementById("loadingSpinner");
            const cartIcon = document.getElementById("cartIcon");
            const btnText = document.getElementById("btnText");
            const cartSidebar = document.getElementById("cartSidebar");
            const closeCart = document.getElementById("closeCart");
            const cartItemsContainer = document.getElementById("cartItems");
            const cartItemCount = document.getElementById("cartItemCount");

            function loadCartItems() {
                fetch("/cart/items")
                    .then(response => response.json())
                    .then(data => {
                        cartItemsContainer.innerHTML = "";
                        let total = 0;
            
                        if (data.cartItems.length === 0) {
                            cartItemsContainer.innerHTML = `<p class="text-gray-500 text-center">Your cart is empty.</p>`;
                        } else {
                            data.cartItems.forEach(item => {
                                let itemPrice = parseFloat(item.price.replace(",", "."));

                                total += itemPrice;
            
                                cartItemsContainer.innerHTML += `
                                    <div class="flex py-2 border-b items-center justify-between">
                                        <div class="flex-1 flex items-start gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" 
                                                class="h-6 w-6 text-blue-600 flex-shrink-0" 
                                                viewBox="0 0 24 24" 
                                                fill="none" 
                                                stroke="currentColor" 
                                                stroke-width="2" 
                                                stroke-linecap="round" 
                                                stroke-linejoin="round">
                                                <path d="M12 7v14"/>
                                                <path d="M16 12h2"/>
                                                <path d="M16 8h2"/>
                                                <path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/>
                                                <path d="M6 12h2"/>
                                                <path d="M6 8h2"/>
                                            </svg>
                                            <div class="leading-tight">
                                                <p class="font-semibold text-gray-800">
                                                    ${item.title}
                                                </p>
                                            </div>
                                        </div>
            
                                        <span class="font-bold text-gray-800 whitespace-nowrap">
                                            €${item.price}
                                        </span>
                                    </div>
                                `;
                            });
                        }
            
                        document.getElementById("cartTotal").textContent = `€${total.toFixed(2)}`;
            
                    })
                    .catch(error => console.error("Error loading cart items:", error));
            }

            function toggleCartSidebar() {
                const isClosed = cartSidebar.classList.contains("translate-x-full");

                if (isClosed) {
                    cartSidebar.classList.remove("translate-x-full");
                    loadCartItems();
                } else {
                    cartSidebar.classList.add("translate-x-full");
                }
            }

            if (addToCartForm) {
                addToCartForm.addEventListener("submit", function (e) {
                    e.preventDefault();

                    loadingSpinner.classList.remove("hidden");
                    cartIcon.classList.add("hidden");
                    btnText.innerText = "Adding...";

                    fetch(this.action, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            btnText.innerText = "Already in Cart";
                            addToCartBtn.classList.add("bg-gray-400", "text-gray-700", "cursor-not-allowed");
                            addToCartBtn.disabled = true;

                            loadCartItems();

                            cartSidebar.classList.remove("translate-x-full");
                        }
                    })
                    .catch(error => console.error("Error adding to cart:", error))
                    .finally(() => {
                        loadingSpinner.classList.add("hidden");
                        cartIcon.classList.remove("hidden");
                    });
                });
            } else {
                console.error("Elemento 'addToCartForm' não encontrado no DOM!");
            }
        });
    </script>
</x-app-layout>

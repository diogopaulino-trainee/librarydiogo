<x-app-layout>
    <x-slot name="header">        
        <h2>{{ __('Manage Reviews') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white shadow-md rounded-lg p-6 border border-blue-300 text-center">
                <h3 class="text-3xl font-semibold text-blue-700 mb-4">Overall Rating</h3>
            
                @if($averageRating)
                    <div class="flex justify-center items-center space-x-2 text-yellow-500 text-3xl">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="relative">
                                @if ($i <= floor($averageRating))
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 17.3l-6.2 3.7 1.6-6.9L2 9.2l7-.6L12 2l3 6.6 7 .6-5.4 4.9 1.6 6.9z"/>
                                    </svg>
                                @elseif ($i == ceil($averageRating) && fmod($averageRating, 1) >= 0.3)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 relative" viewBox="0 0 24 24">
                                        <defs>
                                            <linearGradient id="halfGradient">
                                                <stop offset="50%" stop-color="currentColor"/>
                                                <stop offset="50%" stop-color="white"/>
                                            </linearGradient>
                                        </defs>
                                        <path d="M12 17.3l-6.2 3.7 1.6-6.9L2 9.2l7-.6L12 2l3 6.6 7 .6-5.4 4.9 1.6 6.9z" fill="url(#halfGradient)"/>
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 fill-gray-300" viewBox="0 0 24 24">
                                        <path d="M12 17.3l-6.2 3.7 1.6-6.9L2 9.2l7-.6L12 2l3 6.6 7 .6-5.4 4.9 1.6 6.9z"/>
                                    </svg>
                                @endif
                            </span>
                        @endfor
                    </div>
                    <p class="text-xl text-gray-700 mt-2 font-semibold">
                        {{ number_format($averageRating, 1) }} / 5
                    </p>
                @else
                    <p class="text-gray-500 text-lg">No approved reviews yet.</p>
                @endif
            </div>

            <div class="bg-white shadow-md rounded-lg p-6 border border-blue-300">
                <h3 class="text-3xl font-semibold text-blue-700 mb-4">Pending Reviews</h3>
                @if($pendingReviews->isEmpty())
                    <p class="text-gray-500">No pending reviews at the moment.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-blue-500 shadow-md rounded-lg text-lg">
                            <thead class="bg-blue-600 text-white">
                                <tr>
                                    <th class="px-4 py-2 border-b">Book</th>
                                    <th class="px-4 py-2 border-b">User</th>
                                    <th class="px-4 py-2 border-b">Rating</th>
                                    <th class="px-4 py-2 border-b">Status</th>
                                    <th class="px-4 py-2 border-b">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingReviews as $review)
                                    <tr class="hover:bg-blue-500 hover:text-white group">
                                        <td class="px-4 py-2 text-center">
                                            <a href="{{ route('books.show', $review->book) }}" class="hover:underline group-hover:text-white">
                                                {{ $review->book->title }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <a href="{{ route('admin.users.show', $review->user) }}" class="hover:underline group-hover:text-white">
                                                {{ $review->user->name }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-2 text-center">{{ $review->rating }}/5</td>
                                        <td class="px-4 py-2 text-center">
                                            <span class="px-3 py-1 rounded text-white bg-yellow-500">
                                                Suspended
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <a href="{{ route('reviews.show', $review) }}" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-700 transition">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $pendingReviews->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>

            <div class="flex flex-col md:flex-row justify-between items-center mb-4 w-full">
                <form id="searchForm" action="{{ route('admin.reviews.index') }}" method="GET" class="flex items-center w-full">
                    <div class="flex flex-wrap w-full">
                        <select name="status" onchange="this.form.submit()"
                            class="border border-blue-500 text-blue-600 bg-white px-8 py-2 rounded-md shadow-sm focus:ring-blue-400 focus:border-blue-400 transition duration-200 ease-in-out text-lg mx-1 mt-1">
                            <option value="">All Reviews</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>

                        <div class="relative flex-grow max-w-[400px] mr-2">
                            <input type="text" name="search" placeholder="Search by Book, User, or Rating" 
                                value="{{ request('search') }}" 
                                class="border border-blue-500 px-4 py-2 focus:ring-blue-400 focus:border-blue-400 transition duration-200 ease-in-out w-full pl-10 pr-4 rounded-md shadow-sm text-lg mx-1 mt-1"/>
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <button type="submit" class="btn bg-blue-600 text-lg text-white hover:bg-blue-700 px-4 py-2 rounded-md flex items-center shadow-md min-w-[120px] mx-1 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Search
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white shadow-md rounded-lg p-6 border border-blue-300">
                <h3 class="text-3xl font-semibold text-blue-700 mb-6">Review History</h3>
                @if($historyReviews->isEmpty())
                    <p class="text-gray-500">No reviews in history.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-blue-500 shadow-md rounded-lg text-lg">
                            <thead class="bg-blue-600 text-white">
                                <tr>
                                    <th class="px-4 py-2 border-b">Book</th>
                                    <th class="px-4 py-2 border-b">User</th>
                                    <th class="px-4 py-2 border-b">Rating</th>
                                    <th class="px-4 py-2 border-b">Status</th>
                                    <th class="px-4 py-2 border-b">Admin Justification</th>
                                    <th class="px-4 py-2 border-b">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($historyReviews as $review)
                                    <tr class="hover:bg-blue-500 hover:text-white group">
                                        <td class="px-4 py-2 text-center">
                                            <a href="{{ route('books.show', $review->book) }}" class="hover:underline group-hover:text-white">
                                                {{ $review->book->title }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <a href="{{ route('admin.users.show', $review->user) }}" class="hover:underline group-hover:text-white">
                                                {{ $review->user->name }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-2 text-center">{{ $review->rating }}/5</td>
                                        <td class="px-4 py-2 text-center">
                                            <span class="px-3 py-1 rounded text-white {{ $review->status == 'approved' ? 'bg-green-500' : 'bg-red-500' }}">
                                                {{ ucfirst($review->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            @if($review->admin_justification)
                                                {{ $review->admin_justification }}
                                            @else
                                                <span">No justification</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <a href="{{ route('reviews.show', $review) }}" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-700 transition">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $historyReviews->appends(request()->query())->links() }}
                    </div>
                @endif
                <div class="mt-8">
                    <a href="{{ route('admin.dashboard') }}" class="bg-blue-500 text-white px-4 py-3 text-lg rounded hover:bg-blue-700">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

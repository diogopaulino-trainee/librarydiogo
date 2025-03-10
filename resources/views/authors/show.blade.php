<x-app-layout>
    <x-slot name="header">
        <h2>{{ ('Author Details') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6 border border-blue-500 text-lg">
                <div class="mt-6 flex justify-between items-center my-2">
                    @if(!empty($previousAuthor))
                        <a href="{{ route('authors.show', $previousAuthor->id) }}" 
                           class="bg-red-500 text-white text-lg px-6 py-2 rounded-lg hover:bg-red-700 transition duration-300 shadow-md flex items-center justify-center min-w-[150px]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Previous
                        </a>
                    @else
                        <span class="px-6 py-2 text-gray-400 cursor-not-allowed">← Previous</span>
                    @endif

                    @if(!empty($nextAuthor))
                        <a href="{{ route('authors.show', $nextAuthor->id) }}" 
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
                    <img src="{{ asset(str_starts_with($author->photo, 'images/') ? $author->photo : 'images/' . $author->photo) }}" 
                    alt="Author Photo" 
                    class="w-48 h-48 mx-auto object-cover rounded-full shadow-md">
                </div>

                <div class="grid grid-cols-1 gap-4 text-gray-800">
                    <div><strong>Name:</strong> {{ $author->name }}</div>
                    <div class="text-sm italic text-gray-600">
                        <strong>Added By:</strong> {{ $author->user->name }}
                    </div>
                </div>

                <div class="mt-6 flex justify-between">
                    <a href="{{ route('authors.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Back to List</a>
                    @auth
                        @if(auth()->user()->hasRole('Admin'))
                            <a href="{{ route('authors.edit', $author) }}" class="bg-yellow-400 text-white px-4 py-2 rounded hover:bg-yellow-600">Edit Author</a>
                        @endif
                    @endauth
                </div>                
            </div>
        </div>
    </div>
</x-app-layout>

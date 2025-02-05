<x-app-layout>
    <x-slot name="header">
        <h2>{{ ('Delete Confirmation') }}</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-8 p-6 bg-white shadow-md rounded-lg border border-red-400">
        <h2 class="text-3xl font-extrabold text-red-700 text-center uppercase tracking-wider animate-pulse flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-8 w-8 text-red-700">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" />
            </svg>
            Danger Zone
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-8 w-8 text-red-700">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" />
            </svg>
        </h2>
        <p class="text-center text-red-600 font-semibold mt-2 text-lg">
            Are you absolutely sure you want to <span class="underline decoration-red-500">delete this author</span>? 
        </p>
        <p class="text-center text-sm text-gray-600 italic">
            This action <strong>cannot be undone!</strong> You will permanently lose this data.
        </p>

        <div class="flex justify-center my-4">
            <img src="{{ asset(str_starts_with($author->photo, 'images/') ? $author->photo : 'images/' . $author->photo) }}" alt="Author Photo" class="w-48 h-48 object-cover rounded-full shadow-md border-2 border-red-400">
        </div>

        <div class="space-y-2 text-gray-800 text-center">
            <p><strong>Name:</strong> {{ $author->name }}</p>
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ route('authors.index') }}" class="px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-700 transition duration-300 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Cancel
            </a>
            <form action="{{ route('authors.destroy', $author) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-6 py-2 bg-red-600 text-white font-bold rounded-md hover:bg-red-800 transition duration-300 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Confirm Delete
                </button>
            </form>
        </div>
    </div>

</x-app-layout>

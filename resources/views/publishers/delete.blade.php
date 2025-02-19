<x-app-layout>
    <x-slot name="header">
        <h2>{{ __('Delete Confirmation') }}</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-8 p-6 bg-white shadow-md rounded-lg border border-red-400 text-lg">
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
        <h2 class="text-3xl font-extrabold text-red-700 text-center uppercase tracking-wider animate-pulse flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-10 w-10 text-red-700">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" />
            </svg>
            Danger Zone
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-10 w-10 text-red-700">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" />
            </svg>
        </h2>
        <p class="text-center text-red-600 font-semibold mt-2 text-xl">
            Are you absolutely sure you want to <span class="underline decoration-red-500">delete this publisher</span>? 
        </p>
        <p class="text-center text-base text-gray-600 italic">
            This action <strong>cannot be undone!</strong> You will permanently lose this data.
        </p>

        <div class="flex justify-center my-4">
            <img src="{{ asset(str_starts_with($publisher->logo, 'images/') ? $publisher->logo : 'images/' . $publisher->logo) }}" alt="Publisher Logo" class="w-48 h-48 object-cover rounded-full shadow-md border-2 border-red-400">
        </div>

        <div class="space-y-2 text-gray-800 text-center">
            <p><strong>Name:</strong> {{ $publisher->name }}</p>
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ route('publishers.index') }}" class="px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-700 transition duration-300 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Cancel
            </a>
            <form action="{{ route('publishers.destroy', $publisher) }}" method="POST">
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

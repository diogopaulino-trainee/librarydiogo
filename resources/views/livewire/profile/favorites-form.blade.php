<x-form-section submit="updateFavorites">
    <x-slot name="title">
        {{ __('Favorite Books') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Manage your favorite books here.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-12 sm:col-span-8">
            <x-label value="{{ __('Your Favorite Books') }}" />

            <div class="flex justify-end w-full mt-4 mb-2">
                <div class="flex items-center bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded-lg shadow-md">
                    <span class="text-red-700 font-bold flex items-center text-base me-2">
                        <svg class="w-6 h-6 text-red-700 me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2z"></path>
                        </svg>
                        Enable Delete
                    </span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="enable-delete" class="sr-only peer">
                        <div class="w-10 h-5 bg-gray-300 rounded-full peer peer-checked:bg-red-500 transition-all"></div>
                        <div class="absolute w-4 h-4 bg-white rounded-full shadow-md transition-all left-1 peer-checked:translate-x-5"></div>
                    </label>
                </div>
            </div>

            <div class="mt-2 space-y-3 border border-gray-200 rounded-lg p-3" 
                id="favorite-books-container"
                style="max-height: 400px; overflow-y: auto;">
                    @if($favorites->isEmpty())
                    <div class="flex flex-col items-center justify-center p-4 bg-blue-50 border border-blue-400 text-blue-700 rounded-md shadow-sm">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-blue-600 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2z"></path>
                            </svg>
                            <p class="font-semibold text-2xl">
                                {{ __('You have no favorite books yet.') }}
                            </p>
                        </div>
                        <p class="text-blue-600 text-sm mt-2">
                            {{ __('Add books to your favorites, and they will be listed here.') }}
                        </p>
                    </div>
                    @else
                    @foreach($favorites as $book)
                        <div class="favorite-book bg-white p-4 rounded-lg shadow-md transition duration-300 hover:shadow-lg hover:-translate-y-1 border border-gray-200 hover:border-blue-500">
                            <div class="flex items-center">
                                <img src="{{ asset(str_starts_with($book->cover_image, 'images/') ? $book->cover_image : 'images/' . $book->cover_image) }}" 
                                    alt="{{ $book->title }}" 
                                    class="w-20 h-24 object-cover rounded-md shadow-sm me-4">

                                <div class="flex-grow">
                                    <p class="font-semibold text-gray-900 text-lg">{{ $book->title }}</p>
                                    <p class="text-sm text-gray-700">
                                        <strong>Authors:</strong> {{ $book->authors->pluck('name')->implode(', ') ?: 'Unknown Author' }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        <strong>Publisher:</strong> {{ $book->publisher->name ?? 'Unknown Publisher' }}
                                    </p>
                                </div>

                                <button type="button" wire:click="removeFavorite({{ $book->id }})"
                                        class="delete-btn text-red-500 hover:text-red-700 transition duration-300 hidden">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 5L19 19M6 19L19 5" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </x-slot>
</x-form-section>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const enableDeleteToggle = document.getElementById("enable-delete");

        function updateDeleteButtons() {
            const deleteButtons = document.querySelectorAll(".delete-btn");
            deleteButtons.forEach(btn => {
                if (enableDeleteToggle.checked) {
                    btn.classList.remove("hidden");
                } else {
                    btn.classList.add("hidden");
                }
            });
        }

        enableDeleteToggle.addEventListener("change", updateDeleteButtons);

        const observer = new MutationObserver(updateDeleteButtons);
        observer.observe(document.getElementById("favorite-books-container"), { childList: true, subtree: true });
    });
</script>

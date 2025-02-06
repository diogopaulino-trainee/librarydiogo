<x-form-section submit="updateFavorites">
    <x-slot name="title">
        {{ __('Favorite Books') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Manage your favorite books here.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <ul class="space-y-2">
                @forelse(auth()->user()->favorites as $book)
                    <li class="flex items-center justify-between bg-gray-100 p-3 rounded-md shadow">
                        <span class="font-semibold">{{ $book->title }}</span>
                        <form method="POST" action="{{ route('favorites.remove', $book->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-500 hover:text-red-700 transition duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </form>
                    </li>
                @empty
                    <li class="text-gray-500 italic">You have no favorite books yet.</li>
                @endforelse
            </ul>
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Updated.') }}
        </x-action-message>

        <x-button type="submit">
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-form-section>

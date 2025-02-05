<x-app-layout>
    <x-slot name="header">
        <h2>{{ __('Publisher Details') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6 border border-blue-500">
                <div class="mb-4 text-center">
                    <img src="{{ asset('images/' . $publisher->logo) }}" alt="Publisher Logo" class="w-48 h-48 mx-auto object-cover rounded-full shadow-md">
                </div>

                <div class="grid grid-cols-1 gap-4 text-gray-800">
                    <div><strong>Name:</strong> {{ $publisher->name }}</div>
                    <div class="text-sm italic text-gray-600">
                        <strong>Added By:</strong> {{ $publisher->user->name }}
                    </div>
                </div>

                <div class="mt-6 flex justify-between">
                    <a href="{{ route('publishers.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Back to List</a>
                    <a href="{{ route('publishers.edit', $publisher) }}" class="bg-yellow-400 text-white px-4 py-2 rounded hover:bg-yellow-600">Edit Publisher</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-form-section>
    <x-slot name="title">
        {{ __('Your Book Request History') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Here you can see a history of the books you have requested.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-12 sm:col-span-8">
            <x-label value="{{ __('Your Book Requests') }}" />
            <div class="mt-2 space-y-3 border border-gray-200 rounded-lg p-3" 
                id="book-requests-container"
                style="max-height: 400px; overflow-y: auto;">
                <ul class="space-y-2">
                    @forelse(auth()->user()->requests as $request)
                        @php
                            $daysPassed = \Carbon\Carbon::parse($request->request_date)->diffInDays(now());
                            $progress = min(100, ($daysPassed / 5) * 100);
                        @endphp

                        <li class="bg-white p-4 rounded-lg shadow-md transition duration-300 hover:shadow-lg hover:-translate-y-1 border border-gray-200 hover:border-blue-500 flex items-center">
                            <img src="{{ asset('images/' . $request->book->cover_image) }}" 
                                    alt="{{ $request->book->title }}" 
                                    class="w-20 h-24 object-cover rounded-md shadow-sm me-4">
                            <div class="flex-grow">
                                <p class="font-semibold text-gray-900 text-lg">
                                    <a href="{{ route('books.show', $request->book) }}" class="hover:underline">
                                        {{ $request->book->title ?? 'Unknown Book' }}
                                    </a>
                                </p>
                                <p class="text-sm text-gray-700">
                                    <strong>Requested on:</strong> {{ \Carbon\Carbon::parse($request->request_date)->format('d M, Y') }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    <strong>Expected Return:</strong> {{ \Carbon\Carbon::parse($request->expected_return_date)->format('d M, Y') }}
                                </p>

                                @if($request->status === 'borrowed')
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                                        <div class="bg-blue-500 h-2.5 rounded-full" style="width: {{ $progress }}%;"></div>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-1">
                                        Progress: {{ round($progress) }}%
                                    </p>
                                @endif
                            </div>
                            <div class="mt-2 sm:mt-0 text-sm font-semibold text-white px-3 py-1 rounded-md w-fit"
                                style="background-color:
                                    {{ $request->status === 'borrowed' ? '#facc15' : '' }}
                                    {{ $request->status === 'returned' ? '#10b981' : '' }}
                                    {{ $request->status === 'overdue' ? '#ef4444' : '' }};">
                                {{ ucfirst($request->status) }}
                            </div>
                        </li>
                    @empty
                        <li class="text-gray-500 italic">You have no book requests yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </x-slot>
</x-form-section>

<x-app-layout>
    <x-slot name="header">
        <h2>{{ __('Request Details') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6 border border-blue-500">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center text-center mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">User Photo (At Request Time)</h3>
                        @if($request->user_photo_at_request)
                            <img src="{{ asset(str_starts_with($request->user_photo_at_request, 'storage/') ? $request->user_photo_at_request : 'storage/' . $request->user_photo_at_request) }}" 
                                 alt="User Photo" 
                                 class="w-44 h-44 mx-auto object-cover rounded-full shadow-md">
                        @else
                            <div class="w-44 h-44 bg-gray-300 text-white rounded-full flex items-center justify-center mx-auto text-2xl">
                                {{ strtoupper(substr($request->user_name_at_request ?? 'N/A', 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">Book Cover</h3>
                        @if($request->book->cover_image)
                            <img src="{{ asset(str_starts_with($request->book->cover_image, 'images/') ? $request->book->cover_image : 'images/' . $request->book->cover_image) }}" 
                                 alt="Cover Image" 
                                 class="w-44 h-64 mx-auto object-cover rounded-lg shadow-md">
                        @else
                            <div class="w-44 h-64 bg-gray-200 text-gray-500 rounded-lg flex items-center justify-center mx-auto text-sm">
                                No Cover Available
                            </div>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 text-gray-800 text-lg">
                    <div>
                        <strong>Book:</strong> {{ $request->book->title }}
                    </div>
                
                    <div>
                        <strong>User Name (At Request Time):</strong> 
                        <span class="text-blue-600 font-semibold">{{ $request->user_name_at_request }}</span>
                    </div>
                    
                    <div>
                        <strong>Email (At Request Time):</strong> 
                        <span class="text-blue-600">{{ $request->user_email_at_request }}</span>
                    </div>
                
                    <div>
                        <strong>Request Date:</strong> 
                        {{ \Carbon\Carbon::parse($request->request_date)->format('d M, Y') }}
                    </div>
                    
                    <div>
                        <strong>Expected Return:</strong> 
                        {{ \Carbon\Carbon::parse($request->expected_return_date)->format('d M, Y') }}
                    </div>
                
                    <div>
                        <strong>Actual Return:</strong> 
                        @if($request->actual_return_date)
                            @if(\Carbon\Carbon::parse($request->actual_return_date)->isAfter($request->expected_return_date))
                                <span class="text-red-600 font-semibold">
                                    {{ \Carbon\Carbon::parse($request->actual_return_date)->format('d M, Y') }}
                                </span>
                            @else
                                <span class="text-green-600 font-semibold">
                                    {{ \Carbon\Carbon::parse($request->actual_return_date)->format('d M, Y') }}
                                </span>
                            @endif
                        @else
                            <span class="text-red-500 italic">Not Returned Yet</span>
                        @endif
                    </div>
                    
                    <div>
                        <strong>Days Held:</strong>
                        @if($request->actual_return_date)
                            @if(\Carbon\Carbon::parse($request->actual_return_date)->isAfter($request->expected_return_date))
                                <span class="text-red-600 font-semibold">
                                    {{ \Carbon\Carbon::parse($request->request_date)->diffInDays(\Carbon\Carbon::parse($request->actual_return_date)) }} 
                                    {{ \Carbon\Carbon::parse($request->request_date)->diffInDays(\Carbon\Carbon::parse($request->actual_return_date)) == 1 ? 'day' : 'days' }}
                                </span>
                            @else
                                <span class="text-green-600 font-semibold">
                                    {{ \Carbon\Carbon::parse($request->request_date)->diffInDays(\Carbon\Carbon::parse($request->actual_return_date)) }} 
                                    {{ \Carbon\Carbon::parse($request->request_date)->diffInDays(\Carbon\Carbon::parse($request->actual_return_date)) == 1 ? 'day' : 'days' }}
                                </span>
                            @endif
                        @elseif(\Carbon\Carbon::parse($request->expected_return_date)->isPast())
                            <span class="text-red-600 font-semibold">
                                Overdue since {{ \Carbon\Carbon::parse($request->expected_return_date)->format('d M, Y') }} 
                            </span>
                        @else
                        <span class="text-blue-600 font-semibold">
                            {{ floor(\Carbon\Carbon::parse($request->request_date)->diffInDays(\Carbon\Carbon::now())) }} 
                            {{ floor(\Carbon\Carbon::parse($request->request_date)->diffInDays(\Carbon\Carbon::now())) == 1 ? 'day' : 'days' }}
                        </span>
                        @endif
                    </div>
                    
                </div>
                <div class="mt-6 flex justify-between">
                    <a href="{{ route('requests.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                        Back to List
                    </a>
                    
                    @auth
                        @if(auth()->user()->hasRole('Admin') && $request->status == 'pending')
                            <form action="{{ route('requests.confirm_return', $request) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md transition">
                                    Confirm Return
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

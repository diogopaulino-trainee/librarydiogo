<x-app-layout>
    <x-slot name="header">
        <h2>{{ __('User Details') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-6 border border-blue-500">

                <div class="mb-6 text-gray-800 flex justify-between items-center bg-blue-50 border-2 border-blue-500 rounded-lg shadow-lg p-6">
                        <div class="space-y-2 flex items-center">
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="h-24 w-24 rounded-full object-cover mr-4 border-2 border-blue-500">
                        <div>
                            <p class="text-lg font-semibold flex items-center">
                                <strong class="mr-2">Name:</strong> {{ $user->name }}
                            </p>
                            
                            <p class="text-lg flex items-center">
                                <strong class="mr-2">Email:</strong> {{ $user->email }}
                            </p>
                            
                            <p class="text-lg flex items-center">
                                <strong class="mr-2">Role:</strong> {{ $user->roles->pluck('name')->implode(', ') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="text-gray-800 mb-6">
                    <h3 class="text-xl font-semibold  text-gray-800">Request History</h3>

                    @if($requests->isEmpty())
                        <p class="text-gray-500">No requests have been made by this user.</p>
                    @else
                    <table class="min-w-full table-auto mt-4 border border-gray-300 rounded-lg shadow-md">
                        <thead class="bg-blue-600 text-white">
                                <tr class="border-b">
                                    <th class="px-4 py-2 border-b text-center">Request Number</th>
                                    <th class="px-4 py-2 border-b text-left">Book</th>
                                    <th class="px-4 py-2 border-b whitespace-nowrap text-left">Request Date</th>
                                    <th class="px-4 py-2 border-b whitespace-nowrap text-left">User Photo (At Request Time)</th>
                                    <th class="px-4 py-2 border-b whitespace-nowrap text-left">User Name (At Request Time)</th>
                                    <th class="px-4 py-2 border-b whitespace-nowrap text-left">User Email (At Request Time)</th>
                                    <th class="px-4 py-2 border-b whitespace-nowrap text-left">Expected Return Date</th>
                                    <th class="px-4 py-2 border-b whitespace-nowrap text-left">Actual Return Date</th>
                                    <th class="px-4 py-2 border-b whitespace-nowrap text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests as $request)
                                    <tr class="border-b">
                                        <td class="px-4 py-2 text-center">{{ $request->request_number }}</td>
                                        <td class="px-4 py-2 text-left">
                                            @if($request->book)
                                                <a href="{{ route('books.show', $request->book->id) }}" class="text-blue-600 hover:underline">
                                                    {{ $request->book->title }}
                                                </a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-left">{{ \Carbon\Carbon::parse($request->created_at)->format('d M, Y') }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-left">
                                            @if($request->user_photo_at_request)
                                                <img src="{{ asset(str_starts_with($request->user_photo_at_request, 'storage/') ? $request->user_photo_at_request : 'storage/' . $request->user_photo_at_request) }}" 
                                                    alt="User Photo" 
                                                    class="w-12 h-12 mx-auto object-cover rounded-full shadow-md">
                                            @else
                                                <div class="w-12 h-12 bg-gray-300 text-white rounded-full flex items-center justify-center mx-auto text-2xl">
                                                    {{ strtoupper(substr($request->user_name_at_request ?? 'N/A', 0, 1)) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-left">{{ $request->user_name_at_request ?? 'N/A' }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-left">{{ $request->user_email_at_request ?? 'N/A' }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-left">{{ \Carbon\Carbon::parse($request->expected_return_date)->format('d M, Y') }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-left">
                                            @if($request->actual_return_date)
                                                {{ \Carbon\Carbon::parse($request->actual_return_date)->format('d M, Y') }}
                                            @else
                                                <span class="text-red-600">Not returned yet</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-left">
                                            <span class="px-3 py-1 rounded-md text-white
                                                {{ $request->status == 'pending' ? 'bg-yellow-500' : '' }}
                                                {{ $request->status == 'returned' ? 'bg-green-500' : '' }}
                                                {{ $request->status == 'overdue' ? 'bg-red-500' : '' }}">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <div class="mt-6">
                    <a href="{{ route('admin.users.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Back to Users List</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

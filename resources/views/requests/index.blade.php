<x-app-layout>
    <x-slot name="header">
        <h2>{{ __('Book Requests') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="w-full mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 shadow-md rounded-lg">
                    <p class="font-bold text-xl">{{ number_format($activeRequests) }}</p>
                    <p class="text-sm">Active Requests</p>
                </div>

                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 shadow-md rounded-lg">
                    <p class="font-bold text-xl">{{ number_format($last30DaysRequests) }}</p>
                    <p class="text-sm">Requests in Last 30 Days</p>
                </div>

                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 shadow-md rounded-lg">
                    <p class="font-bold text-xl">{{ number_format($returnedToday) }}</p>
                    <p class="text-sm">Books Returned Today</p>
                </div>
            </div>

            <div class="flex justify-end mb-4">
                <a href="{{ route('books.index') }}" 
                   class="btn btn-primary bg-blue-500 text-white hover:bg-blue-700 transition duration-300 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Request
                </a>
            </div>

            <div class="bg-white overflow-x-auto shadow-xl sm:rounded-lg p-6 border border-blue-500 w-full">
                <table class="min-w-full bg-white border border-blue-500 shadow-md rounded-lg">
                    <thead>
                        <tr class="bg-blue-600 text-white">
                            <th class="px-4 py-2 border-b text-center">Request Number</th>
                            <th class="px-4 py-2 border-b whitespace-nowrap text-left">Book</th>
                            <th class="px-4 py-2 border-b whitespace-nowrap text-left">User</th>
                            <th class="px-4 py-2 border-b whitespace-nowrap text-left">Email</th>
                            <th class="px-4 py-2 border-b whitespace-nowrap text-left">Request Date</th>
                            <th class="px-4 py-2 border-b whitespace-nowrap text-left">Expected Return</th>
                            <th class="px-4 py-2 border-b whitespace-nowrap text-left">Actual Return</th>
                            <th class="px-4 py-2 border-b whitespace-nowrap text-left">Status</th>
                            <th class="px-4 py-2 border-b whitespace-nowrap text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $request)
                        <tr class="hover:bg-blue-500 hover:text-white group">
                            <td class="px-4 py-2 text-center">{{ $request->request_number }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-left">
                                @if($request->book)
                                    <a href="{{ route('books.show', $request->book->id) }}" 
                                       class="text-blue-600 hover:underline font-medium group-hover:text-white">
                                        {{ $request->book->title }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-left">{{ $request->user_name_at_request }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-left">{{ $request->user_email_at_request }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-left">{{ $request->request_date ? \Carbon\Carbon::parse($request->request_date)->format('Y-m-d') : 'N/A' }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-left">{{ $request->expected_return_date ? \Carbon\Carbon::parse($request->expected_return_date)->format('Y-m-d') : 'N/A' }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-left">
                                @if($request->actual_return_date)
                                    {{ \Carbon\Carbon::parse($request->actual_return_date)->format('Y-m-d') }}
                                @else
                                    <span class="text-gray-500 italic group-hover:text-white">Not Returned Yet</span>
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
                    
                            <td class="px-4 py-2 text-center flex items-center justify-left space-x-4 whitespace-nowrap flex-nowrap">                                <a href="{{ route('requests.show', $request) }}" title="View Request Details" class="text-blue-500 group-hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0c0 3.866-4.03 7-9 7s-9-3.134-9-7 4.03-7 9-7 9 3.134 9 7z" />
                                    </svg>
                                </a>
                            
                                @if(auth()->user()->hasRole('Admin') && $request->status === 'pending')
                                    <button onclick="openModal('modal-{{ $request->id }}')" 
                                            class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-md shadow-md transition mt-2">
                                        Confirm Return
                                    </button>
                            
                                    <div id="modal-{{ $request->id }}" class="fixed inset-0 bg-blue-900 bg-opacity-55 hidden items-center justify-center z-50">
                                        <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full border border-blue-300">
                                            <button onclick="closeModal('modal-{{ $request->id }}')" 
                                                    class="absolute top-2 right-2 text-gray-600 hover:text-red-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                            
                                            <h2 class="text-lg font-semibold text-blue-700 mb-2">Confirm Book Return</h2>
                                            <p class="text-gray-600 mb-4">Are you sure you want to mark this book as returned?</p>
                            
                                            <div class="flex justify-end space-x-2">
                                                <button onclick="closeModal('modal-{{ $request->id }}')" 
                                                        class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                                                    Cancel
                                                </button>
                            
                                                <form action="{{ route('requests.confirm_return', $request) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                                                        Confirm
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $requests->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.getElementById(id).classList.add('flex');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.getElementById(id).classList.remove('flex');
        }
    </script>

</x-app-layout>

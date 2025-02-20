<x-app-layout>
    <x-slot name="header">
        <h2>{{ __('Review Details') }}</h2>
    </x-slot>

    <div class="max-w-xl mx-auto py-8">
        <div class="bg-white shadow-md rounded-lg p-6 border border-blue-500">
            @if (session('success'))
                    <div class="max-w-4xl mx-auto mt-12 mb-2">
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-md" role="alert">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-700 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                                </svg>
                                <strong class="font-bold text-green-800">Success!</strong>
                                <span class="ml-2">{{ session('success') }}</span>
                            </div>
                            <button onclick="this.parentElement.style.display='none'" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg class="fill-current h-6 w-6 text-green-700" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <title>Close</title>
                                    <path d="M14.348 5.652a1 1 0 00-1.414 0L10 8.586 7.066 5.652a1 1 0 10-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 11.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934a1 1 0 000-1.414z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                @if (session('error')) 
                    <div class="max-w-4xl mx-auto mt-12 mb-2">
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
                @if ($errors->any())
                    <div class="max-w-4xl mx-auto mt-12 mb-2">
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-md" role="alert">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-red-700 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <strong class="font-bold text-red-800">Validation Error</strong>
                            </div>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button onclick="this.parentElement.style.display='none'" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg class="fill-current h-6 w-6 text-red-700" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <title>Close</title>
                                    <path d="M14.348 5.652a1 1 0 00-1.414 0L10 8.586 7.066 5.652a1 1 0 10-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 11.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934a1 1 0 000-1.414z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif
            <p class="text-lg text-gray-700 mt-4"><strong>Book:</strong> 
                <a href="{{ route('books.show', $review->book->id) }}" 
                   class="text-blue-600 hover:text-blue-800 underline">
                    {{ $review->book->title }}
                </a>
            </p>

            <p class="text-lg text-gray-700"><strong>User:</strong> 
                <a href="{{ route('admin.users.show', $review->user->id) }}" 
                   class="text-blue-600 hover:text-blue-800 underline">
                    {{ $review->user->name }}
                </a>
            </p>

            <p class="text-lg text-gray-700"><strong>Rating:</strong> 
                <span class="text-yellow-500">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= $review->rating) ★ @else ☆ @endif
                    @endfor
                </span>
            </p>

            <p class="text-lg text-gray-700"><strong>Comment:</strong> {{ $review->comment }}</p>

            <p class="text-lg text-gray-700"><strong>Status:</strong> 
                <span class="px-3 py-1 rounded text-white 
                    {{ $review->status == 'suspended' ? 'bg-yellow-500' : ($review->status == 'approved' ? 'bg-green-500' : 'bg-red-500') }}">
                    {{ ucfirst($review->status) }}
                </span>
            </p>

            @if($review->status == 'rejected')
                <p class="text-lg text-gray-700"><strong>Justification:</strong> 
                    <span class="text-gray-600">{{ $review->admin_justification ?? 'N/A' }}</span>
                </p>
            @endif

            <div class="flex flex-col mt-6 text-lg">
                <div class="flex justify-center space-x-4">
                    @if($review->status == 'suspended')
                        <button onclick="openModal('approved')" 
                                class="bg-green-500 text-white px-6 py-3 w-full md:w-1/2 rounded-md hover:bg-green-700 transition shadow-md flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                            </svg>
                            Approve
                        </button>
            
                        <button onclick="openModal('rejected')" 
                                class="bg-red-500 text-white px-6 py-3 w-full md:w-1/2 rounded-md hover:bg-red-700 transition shadow-md flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reject
                        </button>
                    @endif
                </div>
            
                <div class="mt-6">
                    <a href="{{ route('admin.reviews.index') }}" 
                       class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 self-start">
                        Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div id="confirm-modal" class="fixed inset-0 hidden items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="bg-white rounded-lg shadow-md border border-blue-200 max-w-sm w-full p-6 relative">
            <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="text-xl font-semibold text-blue-700 flex items-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11V5a1 1 0 10-2 0v2a1 1 0 102 0zm0 6a1 1 0 11-2 0v-4a1 1 0 112 0v4z" clip-rule="evenodd" />
                </svg>
                Confirm Review Action
            </div>

            <div class="text-lg text-gray-600">
                <p id="modal-message"></p>
                <div id="justification-container" class="hidden mt-4">
                    <label class="block text-gray-700 font-semibold mb-2">Justification (optional):</label>
                    <textarea id="justification" name="admin_justification" class="w-full border p-2 rounded-md" rows="3"></textarea>
                </div>
            </div>

            <div class="flex justify-end mt-4 text-lg">
                <form id="confirmForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" id="modal-status">
                    <input type="hidden" name="admin_justification" id="modal-justification">
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 mr-2 rounded-md hover:bg-green-700">
                        Confirm
                    </button>
                </form>
                <button onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <script>
        function openModal(status) {
            document.getElementById('modal-status').value = status;
            document.getElementById('modal-justification').value = '';

            document.getElementById('confirmForm').action = "{{ route('admin.reviews.update-status', $review) }}";

            if (status === 'approved') {
                document.getElementById('modal-message').textContent = "Are you sure you want to approve this review?";
                document.getElementById('justification-container').classList.add('hidden');
            } else {
                document.getElementById('modal-message').textContent = "Are you sure you want to reject this review?";
                document.getElementById('justification-container').classList.remove('hidden');
            }

            document.getElementById('confirm-modal').classList.remove('hidden');
            document.getElementById('confirm-modal').classList.add('flex');
        }

        document.getElementById('confirmForm').addEventListener('submit', function () {
            document.getElementById('modal-justification').value = document.getElementById('justification').value;
        });

        function closeModal() {
            document.getElementById('confirm-modal').classList.remove('flex');
            document.getElementById('confirm-modal').classList.add('hidden');
        }
    </script>

</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        @if (session('success'))
            <div class="max-w-4xl mx-auto mt-4">
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
        <h2>{{ ('List of Authors') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end mb-4">
                <a href="{{ route('authors.create') }}" class="btn btn-primary bg-blue-500 text-white hover:bg-blue-700 transition duration-300 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create Author
                </a>
            </div>

            <div class="max-full mx-auto mt-8 p-8 bg-white shadow-md rounded-lg border border-blue-500">
                <div class="flex justify-between items-center mb-4">
                    <form action="{{ route('authors.index') }}" method="GET" class="flex items-center space-x-2">
                        <div class="relative">
                            <input type="text" name="search" placeholder="Search by name"
                                   value="{{ request('search') }}"
                                   class="input input-bordered input-primary w-64 pl-10" />
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                        <button type="submit" class="btn bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-md flex items-center shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Search
                        </button>
                        
                        <button type="button" onclick="clearSearch()" class="btn bg-red-500 text-white hover:bg-red-600 px-4 py-2 rounded-md flex items-center shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Clear
                        </button>
                    </form>

                    <div class="flex space-x-2">
                        <a href="{{ route('authors.index', ['sort_by' => 'name', 'order' => 'asc']) }}" 
                           class="btn btn-sm btn-outline">Sort A-Z</a>
                        <a href="{{ route('authors.index', ['sort_by' => 'name', 'order' => 'desc']) }}" 
                           class="btn btn-sm btn-outline">Sort Z-A</a>
                        <a href="{{ route('authors.index', ['sort_by' => 'created_at', 'order' => 'asc']) }}" 
                           class="btn btn-sm btn-outline">Oldest First</a>
                        <a href="{{ route('authors.index', ['sort_by' => 'created_at', 'order' => 'desc']) }}" 
                           class="btn btn-sm btn-outline">Newest First</a>
                        <a href="{{ route('authors.index', ['sort_by' => 'updated_at', 'order' => 'asc']) }}" 
                           class="btn btn-sm btn-outline">Least Updated</a>
                        <a href="{{ route('authors.index', ['sort_by' => 'updated_at', 'order' => 'desc']) }}" 
                           class="btn btn-sm btn-outline">Recently Updated</a>
                    </div>
                </div>

                <table class="min-w-full bg-white border border-blue-500 shadow-md rounded-lg">
                    <thead class="bg-blue-600 text-white">
                        <tr>
                            <th class="px-4 py-2 border-b">Name</th>
                            <th class="px-4 py-2 border-b text-center whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($authors as $author)
                        <tr class="hover:bg-blue-500 hover:text-white group">
                            <td class="px-4 py-2 border-b">{{ $author->name }}</td>
                            <td class="px-4 py-2 border-b text-center whitespace-nowrap space-x-2">
                                <a href="{{ route('authors.show', $author) }}" title="View more details" 
                                   class="text-blue-500 group-hover:text-white transition duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0c0 3.866-4.03 7-9 7s-9-3.134-9-7 4.03-7 9-7 9 3.134 9 7z" />
                                    </svg>
                                </a>
                                <button onclick="openModal('modal-{{ $author->id }}')" 
                                        title="View Timestamps" 
                                        class="text-gray-500 group-hover:text-white transition duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-10 4h10m-9 4h8" />
                                    </svg>
                                </button>
                                <a href="{{ route('authors.edit', $author) }}" class="text-yellow-400 hover:text-yellow-800">Edit</a>
                                <a href="{{ route('authors.delete', $author) }}" class="text-red-600 hover:text-red-800">Delete</a>
                            </td>
                        </tr>

                        <div id="modal-{{ $author->id }}" class="fixed inset-0 bg-blue-900 bg-opacity-55 hidden items-center justify-center z-50">
                            <div class="bg-white rounded-lg shadow-md border border-blue-200 max-w-sm w-full p-6 relative">
                                <button onclick="closeModal('modal-{{ $author->id }}')" class="absolute top-2 right-2 text-gray-500 hover:text-red-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                                <div class="text-lg font-semibold text-blue-700 flex items-center mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11V5a1 1 0 10-2 0v2a1 1 0 102 0zm0 6a1 1 0 11-2 0v-4a1 1 0 112 0v4z" clip-rule="evenodd" />
                                    </svg>
                                    Timestamps
                                </div>

                                <div class="text-sm text-gray-600">
                                    <p><strong>Created At:</strong> {{ $author->created_at->format('d/m/Y H:i') }}</p>
                                    <p><strong>Updated At:</strong> {{ $author->updated_at->format('d/m/Y H:i') }}</p>
                                </div>

                                <div class="flex justify-end mt-4">
                                    <button onclick="closeModal('modal-{{ $author->id }}')" 
                                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">Close</button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $authors->appends(request()->query())->links() }}
                </div>
                
                <div class="flex justify-end mt-6">
                    <a href="{{ route('authors.export') }}" 
                       class="btn bg-green-500 text-white hover:bg-green-700 transition duration-300 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M4 4h16v16H4z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M4 8h16M4 16h16M4 12h16M8 4v16M16 4v16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Export to Excel
                    </a>
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

        function clearSearch() {
            const searchInput = document.querySelector('[name=search]');
            searchInput.value = '';
            searchInput.form.submit();
        }
    </script>
</x-app-layout>

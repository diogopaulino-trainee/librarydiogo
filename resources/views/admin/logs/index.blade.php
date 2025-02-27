<x-app-layout>
    <x-slot name="header">        
        <h2>{{ ('List of Logs') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="max-full mx-auto mt-8 p-8 bg-white shadow-md rounded-lg border border-blue-500">
                @if (session('success'))
                    <div class="max-w-4xl mx-auto mt-2 mb-6 text-lg">
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
                <div class="flex flex-col md:flex-row justify-between items-center mb-4 w-full">
                    <form action="{{ route('admin.logs.index') }}" method="GET" class="flex items-center w-full">
                        <div class="flex flex-wrap items-center w-full sm:w-auto space-x-1">
                            <div class="ml-auto flex items-center space-x-2">
                                <select name="order" onchange="this.form.submit()"
                                    class="border border-blue-500 text-blue-600 bg-white px-8 py-2 rounded-md shadow-sm focus:ring-blue-400 focus:border-blue-400 transition duration-200 ease-in-out text-lg mx-1 mt-1">
                                    <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                                    <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Descending</option>
                                </select>

                                <select name="sort_by" onchange="this.form.submit()"
                                    class="border border-blue-500 text-blue-600 bg-white px-8 py-2 rounded-md shadow-sm focus:ring-blue-400 focus:border-blue-400 transition duration-200 ease-in-out text-lg mx-1 mt-1">
                                    <option value="">Sort By</option>
                                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date</option>
                                    <option value="user_id" {{ request('sort_by') == 'user_id' ? 'selected' : '' }}>User</option>
                                    <option value="module" {{ request('sort_by') == 'module' ? 'selected' : '' }}>Module</option>
                                    <option value="object_id" {{ request('sort_by') == 'object_id' ? 'selected' : '' }}>Object ID</option>
                                    <option value="change_description" {{ request('sort_by') == 'change_description' ? 'selected' : '' }}>Change Description</option>
                                </select>
                            </div>
                            
                            <div class="flex items-center space-x-2 w-full sm:w-auto">
                                <div class="relative w-full sm:w-72">
                                    <input type="text" name="search" placeholder="Search by module"
                                        value="{{ request('search') }}"
                                        class="border border-blue-500 px-4 py-2 rounded-md shadow-sm focus:ring-blue-400 focus:border-blue-400 transition duration-200 ease-in-out w-full sm:w-72 pl-10 text-lg mx-1 mt-1" />
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-lg text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                </div>

                                <button type="submit" class="btn bg-blue-600 text-lg text-white hover:bg-blue-700 px-4 py-2 rounded-md flex items-center shadow-md mx-1 mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Search
                                </button>
                                
                                <button type="button" onclick="clearSearch()" class="btn bg-red-500 text-white text-lg hover:bg-red-600 px-4 py-2 rounded-md flex items-center shadow-md mx-1 mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Clear
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-blue-500 shadow-md rounded-lg text-lg">
                        <thead class="bg-blue-600 text-white">
                            <tr>
                                <th class="px-4 py-2 border-b">Date</th>
                                <th class="px-4 py-2 border-b">Time</th>
                                <th class="px-4 py-2 border-b">User</th>
                                <th class="px-4 py-2 border-b">Module</th>
                                <th class="px-4 py-2 border-b">Object ID</th>
                                <th class="px-4 py-2 border-b">Change</th>
                                <th class="px-4 py-2 border-b">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $log)
                            <tr class="border-b border-gray-300 last:border-b-2 last:border-blue-500 hover:bg-blue-500 hover:text-white group">
                                <td class="px-4 py-2">{{ $log->created_at->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">{{ $log->created_at->format('H:i') }}</td>
                                <td class="px-4 py-2">{{ $log->user_id ? $log->user->name : 'Guest' }}</td>
                                <td class="px-4 py-2">{{ $log->module }}</td>
                                <td class="px-4 py-2">{{ $log->object_id }}</td>
                                <td class="px-4 py-2">{{ $log->change_description }}</td>
                                <td class="px-4 py-2 text-right whitespace-nowrap">
                                    <a href="{{ route('admin.logs.show', $log->id) }}"
                                    class="inline-flex items-center px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-700 transition">
                                        View Details
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $logs->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        function clearSearch() {
            const searchInput = document.querySelector('[name=search]');
            searchInput.value = '';
            searchInput.form.submit();
        }
    </script>
</x-app-layout>

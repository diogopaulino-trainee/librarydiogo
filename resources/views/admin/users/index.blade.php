<x-app-layout>
    <x-slot name="header">
        <h2>{{ ('User Management') }}</h2>
    </x-slot>

    <div class="py-6 text-lg">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end mb-4">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary bg-blue-500 text-white text-lg hover:bg-blue-700 transition duration-300 shadow-md px-4 py-2 rounded-md flex items-center mx-1 mt-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create Admin User
                </a>
            </div>

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
                        <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center w-full">
                            <div class="flex flex-wrap items-center space-x-1">
                                <select name="role" class="border border-blue-500 text-blue-600 bg-white px-8 py-2 rounded-md shadow-sm focus:ring-blue-400 focus:border-blue-400 transition duration-200 ease-in-out text-lg ml-1 mt-1">
                                    <option value="">All Roles</option>
                                    <option value="Citizen" {{ request('role') == 'Citizen' ? 'selected' : '' }}>Citizen</option>
                                    <option value="Admin" {{ request('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                                </select>

                                <div class="relative">
                                    <input type="text" name="search" placeholder="Search by name or email"
                                        value="{{ request('search') }}"
                                        class="border border-blue-500 px-4 py-2 rounded-md shadow-sm focus:ring-blue-400 focus:border-blue-400 transition duration-200 ease-in-out w-64 pl-10 text-lg mt-1" />
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                </div>

                                <button type="submit" class="bg-blue-600 text-white text-lg hover:bg-blue-700 px-4 py-2 rounded-md flex items-center shadow-md mx-1 mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Search
                                </button>

                                <button type="button" onclick="clearSearch()" class="bg-red-500 text-white text-lg hover:bg-red-600 px-4 py-2 rounded-md flex items-center shadow-md mx-1 mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Clear
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white text-lg border border-blue-500 shadow-md rounded-lg">
                            <thead class="bg-blue-600 text-white">
                                <tr>
                                    <th class="px-4 py-2 border-b">Photo</th>
                                    <th class="px-4 py-2 border-b">Name</th>
                                    <th class="px-4 py-2 border-b">Email</th>
                                    <th class="px-4 py-2 border-b">Role</th>
                                    <th class="px-4 py-2 border-b">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($users as $user)
                            <tr class="hover:bg-blue-500 hover:text-white group">
                                <td class="px-4 py-2">
                                    <img class="size-12 rounded-full object-cover" 
                                        src="{{ $user->profile_photo_url }}" 
                                        alt="{{ $user->name }}" />
                                </td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('admin.users.show', $user) }}" class="hover:underline group-hover:text-white">
                                        {{ $user->name }}
                                    </a>
                                </td>
                                <td class="px-4 py-2">{{ $user->email }}</td>
                                <td class="px-4 py-2">{{ $user->roles->pluck('name')->implode(', ') }}</td>
                                <td class="px-4 py-2 text-center whitespace-nowrap flex items-center justify-center space-x-4">
                                    <form action="{{ route('admin.users.change-role', $user) }}" method="POST" class="flex items-center space-x-2">
                                        @csrf
                                        <select name="role" class="border border-blue-500 text-blue-600 bg-white px-4 py-2 rounded-md shadow-sm focus:ring-blue-400 focus:border-blue-400 transition duration-200 ease-in-out min-w-[120px]">
                                            <option value="Citizen" {{ $user->hasRole('Citizen') ? 'selected' : '' }}>Citizen</option>
                                            <option value="Admin" {{ $user->hasRole('Admin') ? 'selected' : '' }}>Admin</option>
                                        </select>
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-5 rounded-md transition duration-200 ease-in-out shadow-md min-w-[100px]">
                                            Update
                                        </button>
                                        <a href="{{ route('admin.users.show', $user) }}" class="text-blue-500 group-hover:text-white">
                                            View Details
                                        </a>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                    <div class="mt-8">
                        <a href="{{ route('admin.dashboard') }}" class="bg-blue-500 text-white px-4 py-3 text-lg rounded hover:bg-blue-700">Back to Dashboard</a>
                    </div>
            </div>
        </div>
    </div>

    <script>
        function clearSearch() {
            document.querySelector('[name=search]').value = '';
            document.querySelector('[name=role]').value = '';
            window.location.href = "{{ route('admin.users.index') }}";
        }
    </script>
</x-app-layout>

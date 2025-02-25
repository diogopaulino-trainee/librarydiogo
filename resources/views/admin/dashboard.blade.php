<x-app-layout>
    <x-slot name="header">
        <h2>{{ __('Admin Dashboard') }}</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            {{-- Gerir Livros --}}
            <div class="flex flex-col h-full">
                <x-panel class="shadow-lg flex-grow transform hover:scale-105 transition duration-300">
                    <a href="{{ route('admin.books.search') }}" 
                       class="flex flex-col items-center justify-center text-center space-y-6 h-full group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-blue-600 group-hover:text-blue-800 transition duration-300 hover:translate-y-[-4px]" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M12 7v14"/>
                            <path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/>
                        </svg>
                        <h2 class="text-3xl font-bold text-blue-700 group-hover:text-blue-800 transition duration-300">
                            Discover & Acquire New Books
                        </h2>
                        <p class="text-gray-600 min-h-[48px]">Explore new titles and enrich the library collection.</p>
                    </a>
                </x-panel>
                <div class="mt-4 p-6 bg-white shadow-md rounded-lg text-center h-full">
                    <h3 class="text-xl font-semibold text-gray-700">Total Books</h3>
                    <p class="text-5xl font-bold text-blue-600">{{ \App\Models\Book::count() }}</p>
                </div>
            </div>

            {{-- Gerir Utilizadores --}}
            <div class="flex flex-col h-full">
                <x-panel class="shadow-lg flex-grow transform hover:scale-105 transition duration-300">
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex flex-col items-center justify-center text-center space-y-6 h-full group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-green-600 group-hover:text-green-800 transition duration-300 hover:translate-y-[-4px]" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <circle cx="18" cy="15" r="3"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M10 15H6a4 4 0 0 0-4 4v2"/>
                            <path d="m21.7 16.4-.9-.3"/>
                            <path d="m15.2 13.9-.9-.3"/>
                            <path d="m16.6 18.7.3-.9"/>
                            <path d="m19.1 12.2.3-.9"/>
                            <path d="m19.6 18.7-.4-1"/>
                            <path d="m16.8 12.3-.4-1"/>
                            <path d="m14.3 16.6 1-.4"/>
                            <path d="m20.7 13.8 1-.4"/>
                        </svg>
                        <h2 class="text-3xl font-bold text-green-700 group-hover:text-green-800 transition duration-300">
                            Manage Users
                        </h2>
                        <p class="text-gray-600 min-h-[48px]">Manage user roles and access permissions.</p>
                    </a>
                </x-panel>
                <div class="mt-4 p-6 bg-white shadow-md rounded-lg text-center h-full">
                    <h3 class="text-xl font-semibold text-gray-700">Registered Users</h3>
                    <p class="text-5xl font-bold text-green-600">{{ \App\Models\User::count() }}</p>
                </div>
            </div>

            {{-- Gerir Reviews --}}
            <div class="flex flex-col h-full">
                <x-panel class="shadow-lg flex-grow transform hover:scale-105 transition duration-300">
                    <a href="{{ route('admin.reviews.index') }}" 
                       class="flex flex-col items-center justify-center text-center space-y-6 h-full group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-yellow-600 group-hover:text-yellow-800 transition duration-300 hover:translate-y-[-4px]" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <rect x="3" y="5" width="6" height="6" rx="1"/>
                            <path d="m3 17 2 2 4-4"/>
                            <path d="M13 6h8"/>
                            <path d="M13 12h8"/>
                            <path d="M13 18h8"/>
                        </svg>
                        <h2 class="text-3xl font-bold text-yellow-700 group-hover:text-yellow-800 transition duration-300">
                            Manage Reviews
                        </h2>
                        <p class="text-gray-600 min-h-[48px]">Approve or reject book reviews.</p>
                    </a>
                </x-panel>
                <div class="mt-4 p-6 bg-white shadow-md rounded-lg text-center h-full">
                    <h3 class="text-xl font-semibold text-gray-700">Pending Reviews</h3>
                    <p class="text-5xl font-bold text-yellow-600">{{ \App\Models\Review::where('status', 'suspended')->count() }}</p>
                </div>
            </div>

            {{-- Gerir Orders --}}
            <div class="flex flex-col h-full">
                <x-panel class="shadow-lg flex-grow transform hover:scale-105 transition duration-300">
                    <a href="{{ route('admin.orders.index') }}" 
                    class="flex flex-col items-center justify-center text-center space-y-6 h-full group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-red-600 group-hover:text-red-800 transition duration-300 hover:translate-y-[-4px]" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"/>
                            <path d="M12 22V12"/>
                            <polyline points="3.29 7 12 12 20.71 7"/>
                            <path d="m7.5 4.27 9 5.15"/>
                        </svg>
                        <h2 class="text-3xl font-bold text-red-700 group-hover:text-red-800 transition duration-300">
                            Manage Orders
                        </h2>
                        <p class="text-gray-600 min-h-[48px]">Track and manage book orders.</p>
                    </a>
                </x-panel>
                <div class="mt-4 p-6 bg-white shadow-md rounded-lg text-center h-full">
                    <h3 class="text-xl font-semibold text-gray-700">Total Orders</h3>
                    <p class="text-5xl font-bold text-red-600">{{ \App\Models\Order::count() }}</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

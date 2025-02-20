<x-app-layout>
    <x-slot name="header">
        <h2>{{ __('Admin Dashboard') }}</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8">
        {{-- Painéis principais --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            {{-- Gerir Livros --}}
            <x-panel class="shadow-lg transform hover:scale-105 transition duration-300">
                <a href="{{ route('admin.books.search') }}" 
                   class="flex flex-col items-center justify-center text-center space-y-6 h-full group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-blue-600 group-hover:text-blue-800 transition duration-300 hover:translate-y-[-4px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5h7a2 2 0 012 2v12a2 2 0 00-2-2H3V5zm11 0h7v12h-7a2 2 0 00-2 2V7a2 2 0 012-2z" />
                    </svg>
                    <h2 class="text-3xl font-bold text-blue-700 group-hover:text-blue-800 transition duration-300">
                        Discover & Acquire New Books
                    </h2>
                    <p class="text-gray-600 min-h-[48px] relative">
                        <span x-data="{ text: '', fullText: 'Explore new titles and enrich the library collection.', i: 0 }"
                              x-init="let interval = setInterval(() => { 
                                  if (i < fullText.length) { 
                                      text += fullText[i]; i++; 
                                  } else { 
                                      clearInterval(interval); 
                                  } 
                              }, 30)"
                              x-text="text"></span>
                    </p>
                </a>
            </x-panel>

            {{-- Gerir Utilizadores --}}
            <x-panel class="shadow-lg transform hover:scale-105 transition duration-300">
                <a href="{{ route('admin.users.index') }}" 
                   class="flex flex-col items-center justify-center text-center space-y-6 h-full group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-green-600 group-hover:text-green-800 transition duration-300 hover:translate-y-[-4px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12h3a3 3 0 013 3v4h-6m-6 0H3v-4a3 3 0 013-3h3m3-3a3 3 0 110-6 3 3 0 010 6z" />
                    </svg>
                    <h2 class="text-3xl font-bold text-green-700 group-hover:text-green-800 transition duration-300">
                        Manage Users
                    </h2>
                    <p class="text-gray-600 min-h-[48px] relative">
                        <span x-data="{ text: '', fullText: 'Manage user roles and access permissions.', i: 0 }"
                              x-init="let interval = setInterval(() => { 
                                  if (i < fullText.length) { 
                                      text += fullText[i]; i++; 
                                  } else { 
                                      clearInterval(interval); 
                                  } 
                              }, 30)"
                              x-text="text"></span>
                    </p>
                </a>
            </x-panel>

            {{-- Gerir Reviews --}}
            <x-panel class="shadow-lg transform hover:scale-105 transition duration-300">
                <a href="{{ route('admin.reviews.index') }}" 
                   class="flex flex-col items-center justify-center text-center space-y-6 h-full group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-yellow-600 group-hover:text-yellow-800 transition duration-300 hover:translate-y-[-4px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h6m-6 4h8M5 3h14a2 2 0 012 2v16l-4-4H5a2 2 0 01-2-2V5a2 2 0 012-2z" />
                    </svg>
                    <h2 class="text-3xl font-bold text-yellow-700 group-hover:text-yellow-800 transition duration-300">
                        Manage Reviews
                    </h2>
                    <p class="text-gray-600 min-h-[48px] relative">
                        <span x-data="{ text: '', fullText: 'Approve or reject book reviews.', i: 0 }"
                              x-init="let interval = setInterval(() => { 
                                  if (i < fullText.length) { 
                                      text += fullText[i]; i++; 
                                  } else { 
                                      clearInterval(interval); 
                                  } 
                              }, 30)"
                              x-text="text"></span>
                    </p>
                </a>
            </x-panel>

        </div>

        {{-- Estatísticas Rápidas --}}
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="p-6 bg-white shadow-md rounded-lg">
                <h3 class="text-xl font-semibold text-gray-700">Total Books</h3>
                <p class="text-5xl font-bold text-blue-600">{{ \App\Models\Book::count() }}</p>
            </div>

            <div class="p-6 bg-white shadow-md rounded-lg">
                <h3 class="text-xl font-semibold text-gray-700">Registered Users</h3>
                <p class="text-5xl font-bold text-green-600">{{ \App\Models\User::count() }}</p>
            </div>

            <div class="p-6 bg-white shadow-md rounded-lg">
                <h3 class="text-xl font-semibold text-gray-700">Pending Reviews</h3>
                <p class="text-5xl font-bold text-yellow-600">{{ \App\Models\Review::where('status', 'suspended')->count() }}</p>
            </div>
        </div>

    </div>
</x-app-layout>

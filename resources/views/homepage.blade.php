<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Library Management</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="//unpkg.com/alpinejs" defer></script>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="font-sans antialiased bg-custom-gradient">
    @include('navigation-menu')

        <div class="wrapper py-10 px-4 lg:px-16 mt-0 flex flex-col justify-start items-center">
            <div class="text-center flex-grow">
                <h1 class="text-4xl font-bold text-blue-700 fade-in mb-24 mt-4">Welcome to the Library Management System</h1>
            </div>
    
            <div x-data="{ books: [], currentIndex: 0 }"
                x-init="fetch('/api/books/covers')
                        .then(response => response.json())
                        .then(data => books = data)" 
                class="relative w-full max-w-4xl mx-auto overflow-hidden rounded-lg shadow-lg border border-blue-300 bg-gray-50 mb-24">

                <div class="relative w-full flex justify-center items-center overflow-hidden h-[500px]">
                    <template x-for="(book, index) in books" :key="book.id">
                        <div x-show="index === currentIndex"
                            :style="{
                                transform: 'translateX(' + (index - currentIndex) * -100 + '%)',
                                opacity: index === currentIndex ? 1 : 0.7
                            }"
                            class="transition-all duration-500 ease-in-out w-1/4 h-full mx-2">
                            <a :href="'/books/' + book.id" class="block w-full h-full">
                                <img :src="(book.cover_image.startsWith('images/') ? 'images/' + book.cover_image : 'images/' + book.cover_image)" 
                                    alt="Book Cover" 
                                    class="w-full h-full object-cover rounded-lg">
                            </a>
                        </div>
                    </template>
                </div>

                <button @click="currentIndex = (currentIndex === 0) ? books.length - 1 : currentIndex - 1"
                        class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-blue-600 text-white px-4 py-2 rounded-full shadow-md hover:bg-blue-700 transition">
                    ❮
                </button>

                <button @click="currentIndex = (currentIndex === books.length - 1) ? 0 : currentIndex + 1"
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-blue-600 text-white px-4 py-2 rounded-full shadow-md hover:bg-blue-700 transition">
                    ❯
                </button>
            </div>
    
            <main>
                <div class="grid gap-6 lg:grid-cols-4 lg:gap-8 fade-in">
                    <x-panel>
                        <a href="{{ route('books.index') }}" class="flex flex-col items-center justify-center text-center space-y-4 h-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-blue-600 group-hover:text-blue-800 transition duration-300 hover:translate-y-[-4px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h7a2 2 0 012 2v12a2 2 0 00-2-2H3V5zm11 0h7v12h-7a2 2 0 00-2 2V7a2 2 0 012-2z" />
                            </svg>
                            <h2 class="text-2xl font-semibold text-blue-700 group-hover:text-blue-800 transition duration-300 hover:translate-y-[-4px]">Books</h2>
                            <p class="text-gray-600 min-h-[48px] relative">
                                <span class="invisible absolute">Explore, add, and manage the library's collection of books.</span>
                                <span x-data="{ text: '', fullText: 'Explore, add, and manage the library\'s collection of books.', i: 0 }"
                                      x-init="let interval = setInterval(() => { 
                                          if (i < fullText.length) { 
                                              text += fullText[i]; i++; 
                                          } else { 
                                              clearInterval(interval); 
                                          } 
                                      }, 40)"
                                      x-text="text"></span>
                            </p>
                        </a>
                    </x-panel>
    
                    <x-panel>
                        <a href="{{ route('authors.index') }}" class="flex flex-col items-center justify-center text-center space-y-4 h-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-green-600 group-hover:text-green-800 transition duration-300 hover:translate-y-[-4px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 00-8 0v2H6a2 2 0 00-2 2v7h16v-7a2 2 0 00-2-2h-2V7z" />
                            </svg>
                            <h2 class="text-2xl font-semibold text-green-700 group-hover:text-green-800 transition duration-300 hover:translate-y-[-4px]">Authors</h2>
                            <p class="text-gray-600 min-h-[48px] relative">
                                <span class="invisible absolute">Manage information about book authors and their works.</span>
                                <span x-data="{ text: '', fullText: 'Manage information about book authors and their works.', i: 0 }"
                                      x-init="let interval = setInterval(() => { 
                                          if (i < fullText.length) { 
                                              text += fullText[i]; i++; 
                                          } else { 
                                              clearInterval(interval); 
                                          } 
                                      }, 40)"
                                      x-text="text"></span>
                            </p>
                        </a>
                    </x-panel>
    
                    <x-panel>
                        <a href="{{ route('publishers.index') }}" class="flex flex-col items-center justify-center text-center space-y-4 h-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-purple-600 group-hover:text-purple-800 transition duration-300 hover:translate-y-[-4px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M9 21V8h6v13M6 21v-6h3v6M15 21v-4h3v4M3 21v-2h18v2M9 8h6M9 12h6M9 16h6" />
                            </svg>
                            <h2 class="text-2xl font-semibold text-purple-700 group-hover:text-purple-800 transition duration-300 hover:translate-y-[-4px]">Publishers</h2>
                            <p class="text-gray-600 min-h-[48px] relative">
                                <span class="invisible absolute">View and manage publisher details for library resources.</span>
                                <span x-data="{ text: '', fullText: 'View and manage publisher details for library resources.', i: 0 }"
                                      x-init="let interval = setInterval(() => { 
                                          if (i < fullText.length) { 
                                              text += fullText[i]; i++; 
                                          } else { 
                                              clearInterval(interval); 
                                          } 
                                      }, 40)"
                                      x-text="text"></span>
                            </p>
                        </a>
                    </x-panel>
    
                    <x-panel>
                        <a href="{{ route('requests.index') }}" class="flex flex-col items-center justify-center text-center space-y-4 h-full">
                            <svg class="h-24 w-24 text-orange-600" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                            </svg>
                            <h2 class="text-2xl font-semibold text-orange-700 group-hover:text-orange-800 transition duration-300 hover:translate-y-[-4px]">Requests</h2>
                            <p class="text-gray-600 min-h-[48px] relative">
                                <span class="invisible absolute">Manage book requests and library inquiries.</span>
                                <span x-data="{ text: '', fullText: 'Manage book requests and library inquiries.', i: 0 }"
                                      x-init="let interval = setInterval(() => { 
                                          if (i < fullText.length) { 
                                              text += fullText[i]; i++; 
                                          } else { 
                                              clearInterval(interval); 
                                          } 
                                      }, 40)"
                                      x-text="text"></span>
                            </p>
                        </a>
                    </x-panel>
                </div>
                <div class="mt-12 text-center fade-in">
                    <p class="text-xl text-gray-700 italic mb-8">📖 "A library is not a luxury but one of the necessities of life." — Henry Ward Beecher</p>
                
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-1 justify-center mt-2">
                        <div x-data="{ count: 0 }" x-init="fetch('/api/books/count')
                            .then(response => response.json())
                            .then(data => count = data.count)">
                            <span class="text-2xl font-semibold text-blue-700 leading-tight" x-text="count"></span>
                            <p class="text-gray-500 text-sm mt-0">Books Available</p>
                        </div>
                
                        <div x-data="{ count: 0 }" x-init="fetch('/api/authors/count')
                            .then(response => response.json())
                            .then(data => count = data.count)">
                            <span class="text-2xl font-semibold text-green-700 leading-tight" x-text="count"></span>
                            <p class="text-gray-600 text-sm mt-0">Authors Registered</p>
                        </div>
                
                        <div x-data="{ count: 0 }" x-init="fetch('/api/publishers/count')
                            .then(response => response.json())
                            .then(data => count = data.count)">
                            <span class="text-2xl font-semibold text-purple-700 leading-tight" x-text="count"></span>
                            <p class="text-gray-600 text-sm mt-0">Publishers</p>
                        </div>
                
                        <div x-data="{ count: 0 }" x-init="fetch('/api/users/count')
                            .then(response => response.json())
                            .then(data => count = data.count)">
                            <span class="text-2xl font-semibold text-orange-700 leading-tight" x-text="count"></span>
                            <p class="text-gray-600 text-sm mt-0">Users Registered</p>
                        </div>
                    </div>
                </div>
            </main>
        </div>

    <footer class="text-center py-6 bg-gradient-to-r from-blue-600 to-indigo-800 text-white mt-12">
        <p class="text-lg font-semibold">
            Want to know more about me? 
            <a href="{{ route('about.me') }}" 
               class="underline hover:text-white transition duration-300 hover:opacity-80">
                Click here to learn more!
            </a>
        </p>
    </footer>
</body>
</html>

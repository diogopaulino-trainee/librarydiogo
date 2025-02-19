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
<body class="font-sans antialiased bg-custom-gradient text-lg">
    @include('navigation-menu')

        <div class="wrapper py-10 px-4 lg:px-16 mt-0 flex flex-col justify-start items-center">
            <div class="text-center flex-grow">
                <h2 class="text-3xl font-bold mb-20 mt-8">Welcome to the Library Management System</h2>
            </div>

                @if (session('success'))
                    <div class="w-full sm:w-2/4 mx-auto mt-2 mb-4">
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-md text-center break-words" role="alert">
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
                    <div class="w-full sm:w-2/4 mx-auto mt-2 mb-4">
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-md text-center break-words" role="alert">
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
    
                <div x-data="{ 
                    books: [], 
                    currentIndex: 0, 
                    intervalId: null, 
                    startRotation() { 
                        this.intervalId = setInterval(() => {
                            this.currentIndex = (this.currentIndex + 1) % this.books.length;
                        }, 3000);
                    }, 
                    stopRotation() { 
                        clearInterval(this.intervalId); 
                    } 
                }"
                x-init="
                    fetch('/api/books/covers')
                        .then(response => response.json())
                        .then(data => {
                            books = data;
                            startRotation();
                        });

                    window.startRotation = () => startRotation();"

                class="relative w-full max-w-4xl mx-auto overflow-hidden rounded-lg shadow-lg border border-blue-300 bg-gray-50 mb-16">

                <div class="relative w-full flex justify-start items-center overflow-hidden h-[600px]">
                    <template x-for="(book, index) in books" :key="book.id">
                        <div x-show="index === currentIndex"
                            :style="{
                                transform: 'translateX(' + (index - currentIndex) * -100 + '%)',
                                opacity: index === currentIndex ? 1 : 0.7
                            }"
                            class="transition-all duration-500 ease-in-out w-full h-full mx-0 flex items-center">
                            
                            <div class="w-3/4 h-full flex justify-center items-center">
                                <a :href="'/books/' + book.id" class="block w-full h-full">
                                    <img :src="book.cover_image.startsWith('http') ? book.cover_image : '/images/' + book.cover_image.replace(/^images\//, '')"
                                            alt="Book Cover" 
                                            class="w-auto h-full object-cover rounded-lg">
                                </a>
                            </div>

                            <div class="w-2/3 p-8 mr-20">
                                <h3 class="text-3xl font-bold text-blue-700 mb-2">
                                    <a :href="'/books/' + book.id" class="hover:underline" x-text="book.title"></a>
                                </h3>
                                <div class="text-lg text-gray-700 space-y-2">
                                    <p><span class="font-semibold">ISBN:</span> <span x-text="book.isbn || 'N/A'"></span></p>
                                    
                                    <p>
                                        <span class="font-semibold">Author(s):</span> 
                                        <span class="text-blue-600">
                                            <template x-for="(author, index) in book.authors" :key="author.id">
                                                <span>
                                                    <a :href="'/authors/' + author.id" class="hover:underline" x-text="author.name"></a>
                                                    <span x-text="index < book.authors.length - 1 ? ', ' : ''"></span>
                                                </span>
                                            </template>
                                        </span>
                                    </p>
                            
                                    <p>
                                        <span class="font-semibold">Publisher:</span> 
                                        <a :href="'/publishers/' + book.publisher?.id" 
                                           class="text-blue-600 hover:underline" 
                                           x-text="book.publisher?.name || 'Unknown'">
                                        </a>
                                    </p>
                            
                                    <p>
                                        <span class="font-semibold">Availability:</span> 
                                        <span :class="book.status === 'available' ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold'" 
                                              x-text="book.status === 'available' ? 'Available' : 'Unavailable'">
                                        </span>
                                    </p>

                                    <div class="mt-4">
                                        <template x-if="book.status === 'available' && {{ auth()->check() ? (auth()->user()->hasRole('Citizen') ? 'true' : 'false') : 'false' }}">
                                            <button @click="stopRotation(); openRequestModal(book.id)"
                                                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                                                Request
                                            </button>
                                        </template>
                                        
                                        <template x-if="book.status !== 'available' || {{ auth()->check() ? (auth()->user()->hasRole('Citizen') ? 'false' : 'true') : 'true' }}">
                                            <button class="bg-gray-400 text-white px-4 py-2 rounded cursor-not-allowed" disabled>
                                                Request
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <div :id="'request-modal-' + book.id" class="fixed inset-0 bg-blue-900 bg-opacity-55 hidden items-center justify-center z-50">
                                <div class="bg-white rounded-lg shadow-md border border-blue-200 max-w-sm m-auto p-6 relative" style="top: -10%; transform: translateY(-50%);">
                                    <button @click="startRotation(); closeModal(book.id)" class="absolute top-2 right-2 text-gray-500 hover:text-red-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                            
                                    <div class="text-xl font-semibold text-blue-700 flex items-center mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11V5a1 1 0 10-2 0v2a1 1 0 102 0zm0 6a1 1 0 11-2 0v-4a1 1 0 112 0v4z" clip-rule="evenodd" />
                                        </svg>
                                        Confirm Request
                                    </div>
                            
                                    <div class="text-base text-gray-600">
                                        <p><strong>Book:</strong> <span x-text="book.title"></span></p>
                                        <p><strong>Are you sure you want to request this book?</strong></p>
                                    </div>
                            
                                    <div class="flex justify-end mt-4">
                                        <form :id="'requestForm-' + book.id" :action="'{{ route('requests.store', ':id') }}'.replace(':id', book.id)" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 mr-2 rounded-md hover:bg-blue-700">
                                                Confirm
                                            </button>
                                        </form>
                                        <button @click="closeModal(book.id)" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </template>
                </div>

                <button @click="currentIndex = (currentIndex === 0) ? books.length - 1 : currentIndex - 1"
                    class="absolute left-4 top-1/2 -translate-y-1/2 bg-gradient-to-r from-blue-500 to-blue-700 text-white px-6 py-3 rounded-full shadow-lg hover:scale-105 transition duration-200">
                    ❮
                </button>

                <button @click="currentIndex = (currentIndex === books.length - 1) ? 0 : currentIndex + 1"
                    class="absolute right-4 top-1/2 -translate-y-1/2 bg-gradient-to-r from-blue-500 to-blue-700 text-white px-6 py-3 rounded-full shadow-lg hover:scale-105 transition duration-200">
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
                            <h2 class="text-3xl font-semibold text-blue-700 group-hover:text-blue-800 transition duration-300 hover:translate-y-[-4px]">Books</h2>
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
                            <h2 class="text-3xl font-semibold text-green-700 group-hover:text-green-800 transition duration-300 hover:translate-y-[-4px]">Authors</h2>
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
                            <h2 class="text-3xl font-semibold text-purple-700 group-hover:text-purple-800 transition duration-300 hover:translate-y-[-4px]">Publishers</h2>
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
                            <h2 class="text-3xl font-semibold text-orange-700 group-hover:text-orange-800 transition duration-300 hover:translate-y-[-4px]">Requests</h2>
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
                    <p class="text-3xl text-white font-bold italic mb-8">📖 "A library is not a luxury but one of the necessities of life." — Henry Ward Beecher</p>
                
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-1 justify-center mt-2 text-base">
                        <div x-data="{ count: 0 }" x-init="fetch('/api/books/count')
                            .then(response => response.json())
                            .then(data => count = data.count)">
                            <span class="text-4xl font-semibold text-blue-700 leading-tight" x-text="count"></span>
                            <p class="text-white text-xl mt-0">Books Available</p>
                        </div>
                
                        <div x-data="{ count: 0 }" x-init="fetch('/api/authors/count')
                            .then(response => response.json())
                            .then(data => count = data.count)">
                            <span class="text-4xl font-semibold text-green-700 leading-tight" x-text="count"></span>
                            <p class="text-white text-xl mt-0">Authors Registered</p>
                        </div>
                
                        <div x-data="{ count: 0 }" x-init="fetch('/api/publishers/count')
                            .then(response => response.json())
                            .then(data => count = data.count)">
                            <span class="text-4xl font-semibold text-purple-700 leading-tight" x-text="count"></span>
                            <p class="text-white text-xl mt-0">Publishers</p>
                        </div>
                
                        <div x-data="{ count: 0 }" x-init="fetch('/api/users/count')
                            .then(response => response.json())
                            .then(data => count = data.count)">
                            <span class="text-4xl font-semibold text-orange-700 leading-tight" x-text="count"></span>
                            <p class="text-white text-xl mt-0">Users Registered</p>
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

    <script>
        function openRequestModal(bookId) {
            document.getElementById(`request-modal-${bookId}`).classList.remove('hidden');
            document.getElementById(`request-modal-${bookId}`).classList.add('flex');
        }

        function closeModal(bookId) {
            document.getElementById(`request-modal-${bookId}`).classList.add('hidden');
            document.getElementById(`request-modal-${bookId}`).classList.remove('flex');
            
            if (typeof window.startRotation === "function") {
                window.startRotation();
            }
        }
    </script>
</body>
</html>

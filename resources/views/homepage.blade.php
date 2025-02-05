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
<body class="font-sans antialiased bg-gray-50">
    @include('navigation-menu')

    <div class="wrapper py-10 px-4 lg:px-16">
        <h1 class="text-4xl font-bold text-center text-blue-700 mb-8">Welcome to the Library Management System</h1>

        <main>
            <div class="grid gap-6 lg:grid-cols-3 lg:gap-8">
                <x-panel>
                    <a href="{{ route('books.index') }}" class="flex flex-col items-center justify-center text-center space-y-4 h-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-blue-600 group-hover:text-blue-800 transition duration-300 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h7a2 2 0 012 2v12a2 2 0 00-2-2H3V5zm11 0h7v12h-7a2 2 0 00-2 2V7a2 2 0 012-2z" />
                        </svg>
                        <h2 class="text-2xl font-semibold text-blue-700 group-hover:text-blue-800 transition duration-300">Books</h2>
                        <p class="text-gray-600">Explore, add, and manage the library's collection of books.</p>
                    </a>
                </x-panel>

                <x-panel>
                    <a href="{{ route('authors.index') }}" class="flex flex-col items-center justify-center text-center space-y-4 h-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-green-600 group-hover:text-green-800 transition duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 00-8 0v2H6a2 2 0 00-2 2v7h16v-7a2 2 0 00-2-2h-2V7z" />
                        </svg>
                        <h2 class="text-2xl font-semibold text-green-700 group-hover:text-green-800 transition duration-300">Authors</h2>
                        <p class="text-gray-600">Manage information about book authors and their works.</p>
                    </a>
                </x-panel>

                <x-panel>
                    <a href="{{ route('publishers.index') }}" class="flex flex-col items-center justify-center text-center space-y-4 h-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-purple-600 group-hover:text-purple-800 transition duration-300 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M9 21V8h6v13M6 21v-6h3v6M15 21v-4h3v4M3 21v-2h18v2M9 8h6M9 12h6M9 16h6" />
                        </svg>
                        <h2 class="text-2xl font-semibold text-purple-700 group-hover:text-purple-800 transition duration-300">Publishers</h2>
                        <p class="text-gray-600">View and manage publisher details for library resources.</p>
                    </a>
                </x-panel>
            </div>
        </main>
    </div>

    <footer class="text-center py-4 text-gray-500">
        <p>
            Want to know more about me? 
            <a href="{{ route('about.me') }}" class="text-blue-600 hover:text-blue-800 font-semibold underline transition duration-300">
                Click here to learn more!
            </a>
        </p>
    </footer>
</body>
</html>

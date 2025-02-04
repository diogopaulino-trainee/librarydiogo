<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="font-sans antialiased">
        @include('navigation-menu')
    
        <div class="wrapper">
            <main>
                <div class="grid gap-6 lg:grid-cols-3 lg:gap-8">
                    <x-panel>
                        <a href="{{ route('books.index') }}" class="block text-center text-xl font-semibold">Books</a>
                    </x-panel>
    
                    <x-panel>
                        <a href="{{ route('authors.index') }}" class="block text-center text-xl font-semibold">Authors</a>
                    </x-panel>
    
                    <x-panel>
                        <a href="{{ route('publishers.index') }}" class="block text-center text-xl font-semibold">Publishers</a>
                    </x-panel>
                </div>
            </main>
        </div>
    
        <footer>
            Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
        </footer>
    </body>
</html>

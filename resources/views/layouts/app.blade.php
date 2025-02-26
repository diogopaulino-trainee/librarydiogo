<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @livewireStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
    </head>
    <body class="font-sans antialiased bg-custom-gradient pt-16">
        <x-banner />

        <div class="min-h-screen bg-custom-transparent">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts    

        <footer class="text-center py-6 bg-gradient-to-r from-blue-600 to-indigo-800 text-white mt-12">
            <p class="text-lg font-semibold">
                Want to know more about me? 
                <a href="{{ route('about.me') }}" 
                   class="underline hover:text-white transition duration-300 hover:opacity-80">
                    Click here to learn more!
                </a>
            </p>
        </footer>


        <!-- Carrinho Lateral -->
        <div id="cartSidebar" class="fixed top-16 right-0 w-80 h-full bg-white shadow-xl transform translate-x-full transition-transform ease-in-out duration-300 z-50">
            <div class="p-4 flex justify-between items-center border-b">
                <h2 class="text-lg font-bold">Shopping Cart</h2>
                <button id="closeCart" class="text-gray-500 hover:text-gray-700">✖</button>
            </div>
            <div class="p-4" id="cartItems">
                <!-- Itens do carrinho serão adicionados aqui -->
            </div>
            <div class="p-4 border-t flex justify-between items-center">
                <span class="font-semibold text-gray-700">Total:</span>
                <span id="cartTotal" class="text-lg font-bold text-gray-900">€0.00</span>
            </div>
            <div class="p-4 border-t flex flex-col gap-2">
                <a href="{{ route('cart.index') }}" 
                    class="bg-gray-300 text-black w-full py-2 rounded-md text-lg text-center flex items-center justify-center gap-2 hover:bg-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20h9"/>
                        <path d="M16.5 3.5a2.121 2.121 0 1 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/>
                    </svg>
                    See and Edit
                </a>

                <a href="{{ route('orders.create') }}" 
                    class="bg-orange-500 text-white w-full py-2 rounded-md text-lg text-center flex items-center justify-center gap-2 hover:bg-orange-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="8" cy="21" r="1"/>
                        <circle cx="19" cy="21" r="1"/>
                        <path d="M2.5 2.5h2l3.6 9.9a1 1 0 0 0 1 .7h7.9a1 1 0 0 0 1-.7L21 5H5"/>
                    </svg>
                    Checkout
                </a>
            </div>
        </div>
    </body>
</html>

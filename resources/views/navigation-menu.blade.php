<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('homepage') }}">
                        <x-application-mark />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link href="{{ route('books.index') }}" :active="request()->routeIs('books.*')" class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6 4v16c0 .55.45 1 1 1h13v-2H8V4H6zm3 0v14h11V4H9zM4 2h13c1.1 0 2 .9 2 2v14c0 1.1-.9 2-2 2H4V2z" />
                        </svg>
                        <span>{{ __('Books') }}</span>
                    </x-nav-link>

                    <x-nav-link href="{{ route('authors.index') }}" :active="request()->routeIs('authors.*')" class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12c2.67 0 8 1.34 8 4v2H4v-2c0-2.66 5.33-4 8-4zm0-2a4 4 0 100-8 4 4 0 000 8z" />
                        </svg>
                        <span>{{ __('Authors') }}</span>
                    </x-nav-link>

                    <x-nav-link href="{{ route('publishers.index') }}" :active="request()->routeIs('publishers.*')" class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M4 22V10L12 2L20 10V22H15V15H9V22H4Z" />
                        </svg>
                        <span>{{ __('Publishers') }}</span>
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @auth
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="size-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ Auth::user()->name }}
                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                            @endauth

                            @guest
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <x-nav-link href="{{ route('login') }}" class="flex items-center space-x-2">
                                    <span>{{ __('Login') }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H3m6-6l-6 6 6 6M21 4v16" />
                                    </svg>
                                </x-nav-link>

                                <x-nav-link href="{{ route('register') }}" class="flex items-center space-x-2">
                                    <span>{{ __('Register') }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20h9M16.5 3.5l4 4-11 11H5v-4L16.5 3.5z" />
                                    </svg>
                                </x-nav-link>
                            </div>
                            @endguest
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <div class="border-t border-gray-200"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('books.index') }}" :active="request()->routeIs('books.*')">
                {{ __('Books') }}
            </x-responsive-nav-link>
        
            <x-responsive-nav-link href="{{ route('authors.index') }}" :active="request()->routeIs('authors.*')">
                {{ __('Authors') }}
            </x-responsive-nav-link>
        
            <x-responsive-nav-link href="{{ route('publishers.index') }}" :active="request()->routeIs('publishers.*')">
                {{ __('Publishers') }}
            </x-responsive-nav-link>
        </div>      

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                @auth
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <div class="shrink-0 me-3">
                            <img class="size-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                        </div>
                    @endif
        
                    <div>
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                @endauth
        
                @guest
                    <div>
                        <a href="{{ route('login') }}" class="text-gray-800 font-medium">Login</a>
                        <a href="{{ route('register') }}" class="text-gray-500 text-sm">Register</a>
                    </div>
                @endguest
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}"
                                   @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

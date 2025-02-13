<nav x-data="{ open: false }" class="bg-white border-b border-gray-200">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 fade-in">
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
                            <path d="M6 6V2H18V6H21C22.1 6 23 6.9 23 8V16C23 17.1 22.1 18 21 18H18V22H6V18H3C1.9 18 1 17.1 1 16V8C1 6.9 1.9 6 3 6H6ZM6 8H18V4H6V8ZM3 16H6V12H18V16H21V8H3V16ZM16 14H8V20H16V14Z"/>
                        </svg>
                        <span>{{ __('Publishers') }}</span>
                    </x-nav-link>
                    
                    @auth
                        <x-nav-link href="{{ route('requests.index') }}" :active="request()->routeIs('requests.*')" class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9 2L2 7v14h20V7l-7-5H9zm0 2h6l5 3H4l5-3zm-5 5h16v10H4V9zm7 1v6h2v-6h-2zm-4 0v6h2v-6H7zm8 0v6h2v-6h-2z"/>
                            </svg>
                            <span>{{ __('Requests') }}</span>
                        </x-nav-link> 
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="size-12 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
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
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-2 text-gray-800 text-sm border-b">
                                <p class="font-semibold">{{ Auth::user()->name }}</p>
                                <p class="text-gray-500">{{ Auth::user()->email }}</p>
                            </div>
                        
                            <!-- Profile Link -->
                            <x-dropdown-link href="{{ route('profile.show') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 inline mr-2" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 12c2.67 0 8 1.34 8 4v2H4v-2c0-2.66 5.33-4 8-4zm0-2a4 4 0 100-8 4 4 0 000 8z" />
                                </svg>
                                <span>{{ __('Profile') }}</span>
                            </x-dropdown-link>
                        
                            <div class="border-t border-gray-200"></div>

                            @role('Admin') 
                            <x-dropdown-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 inline mr-2" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5s-3 1.34-3 3 1.34 3 3 3zm8 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4zM8 13c-2.67 0-8 1.34-8 4v2h6v-2c0-2.66 5.33-4 8-4h-6z" />
                                </svg>
                                <span>{{ __('Manage Users') }}</span>
                            </x-dropdown-link>
                            @endrole

                            <div class="border-t border-gray-200"></div>
                        
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <x-dropdown-link href="#" @click.prevent="$root.submit();">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H3m6-6l-6 6 6 6M21 4v16" />
                                    </svg>
                                    <span class="text-red-500">{{ __('Log Out') }}</span>
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
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
                </div>
            </div>
            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-blue-500 hover:text-blue-600 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-blue-600 transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('books.index') }}" :active="request()->routeIs('books.*')">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500 inline" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M6 4v16c0 .55.45 1 1 1h13v-2H8V4H6zm3 0v14h11V4H9zM4 2h13c1.1 0 2 .9 2 2v14c0 1.1-.9 2-2 2H4V2z" />
                </svg>
                <span>{{ __('Books') }}</span>
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('authors.index') }}" :active="request()->routeIs('authors.*')">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500 inline" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 12c2.67 0 8 1.34 8 4v2H4v-2c0-2.66 5.33-4 8-4zm0-2a4 4 0 100-8 4 4 0 000 8z" />
                </svg>
                <span>{{ __('Authors') }}</span>
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('publishers.index') }}" :active="request()->routeIs('publishers.*')">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500 inline" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M6 6V2H18V6H21C22.1 6 23 6.9 23 8V16C23 17.1 22.1 18 21 18H18V22H6V18H3C1.9 18 1 17.1 1 16V8C1 6.9 1.9 6 3 6H6ZM6 8H18V4H6V8ZM3 16H6V12H18V16H21V8H3V16ZM16 14H8V20H16V14Z"/>                </svg>
                <span>{{ __('Publishers') }}</span>
            </x-responsive-nav-link>

            @auth
                <x-responsive-nav-link href="{{ route('requests.index') }}" :active="request()->routeIs('requests.*')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500 inline" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M9 2L2 7v14h20V7l-7-5H9zm0 2h6l5 3H4l5-3zm-5 5h16v10H4V9zm7 1v6h2v-6h-2zm-4 0v6h2v-6H7zm8 0v6h2v-6h-2z"/>
                    </svg>
                    <span>{{ __('Requests') }}</span>
                </x-responsive-nav-link>
            @endauth
        </div>      

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="size-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500 inline" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.67 0 8 1.34 8 4v2H4v-2c0-2.66 5.33-4 8-4zm0-2a4 4 0 100-8 4 4 0 000 8z" />
                    </svg>
                    <span>{{ __('Profile') }}</span>
                </x-responsive-nav-link>

                @role('Admin') 
                <x-responsive-nav-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500 inline" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5s-3 1.34-3 3 1.34 3 3 3zm8 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4zM8 13c-2.67 0-8 1.34-8 4v2h6v-2c0-2.66 5.33-4 8-4h-6z" />
                    </svg>
                    <span>{{ __('Manage Users') }}</span>
                </x-responsive-nav-link>
                @endrole

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}"
                                @click.prevent="$root.submit();">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-red-500 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H3m6-6l-6 6 6 6M21 4v16" />
                        </svg>
                        <span class="text-red-500">{{ __('Log Out') }}</span>
                    </x-responsive-nav-link>
                </form>
            </div>
            @endauth

            @guest
            <div class="flex items-center px-4">
                <div>
                    <a href="{{ route('login') }}" class="flex items-center text-gray-800 font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500 rotate-180 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H3m6-6l-6 6 6 6M21 4v16" />
                        </svg>
                        <span>Login</span>
                    </a>
                    <a href="{{ route('register') }}" class="flex items-center text-gray-800 font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20h9M16.5 3.5l4 4-11 11H5v-4L16.5 3.5z" />
                        </svg>
                        <span>Register</span>
                    </a>
                </div>
            </div>
            @endguest
        </div>
    </div>
</nav>
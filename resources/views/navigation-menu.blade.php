<nav x-data="{ open: false }" class="fixed top-0 left-0 w-full bg-white border-b border-gray-200 z-20 overflow-visible">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 fade-in">
    <div class="absolute bottom-0 left-0 w-[60%] h-1 bg-gradient-to-r from-transparent via-blue-700 to-transparent animate-glowing"></div>
    <style>
        @keyframes glowing {
            0% { transform: translateX(-100%); opacity: 0.3; }
            50% { opacity: 1; }
            100% { transform: translateX(100%); opacity: 0.3; }
        }
    
        .animate-glowing {
            animation: glowing 2.5s infinite linear;
        }
    </style> 
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="3" rx="2"/>
                            <path d="M7 7v10"/>
                            <path d="M11 7v10"/>
                            <path d="m15 7 2 10"/>
                        </svg>
                        <span>{{ __('Books') }}</span>
                    </x-nav-link>

                    <x-nav-link href="{{ route('authors.index') }}" :active="request()->routeIs('authors.*')" class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m21 17-2.156-1.868A.5.5 0 0 0 18 15.5v.5a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1c0-2.545-3.991-3.97-8.5-4a1 1 0 0 0 0 5c4.153 0 4.745-11.295 5.708-13.5a2.5 2.5 0 1 1 3.31 3.284"/>
                            <path d="M3 21h18"/>
                        </svg>
                        <span>{{ __('Authors') }}</span>
                    </x-nav-link>

                    <x-nav-link href="{{ route('publishers.index') }}" :active="request()->routeIs('publishers.*')" class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                            <path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"/>
                            <rect x="6" y="14" width="12" height="8" rx="1"/>
                        </svg>
                        <span>{{ __('Publishers') }}</span>
                    </x-nav-link>
                    
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @role('Citizen')
                    <div class="relative cursor-pointer">
                        <button id="cartIconBtn" class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500 hover:text-blue-700 transition mt-2" 
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 3h2l3 10h11l3-7H6"></path>
                                <circle cx="9" cy="20" r="2"></circle>
                                <circle cx="18" cy="20" r="2"></circle>
                            </svg>
                        </button>
                    </div>
                @endrole

                <!-- Settings Dropdown -->
                <div class="ms-3 relative z-50">
                    @auth
                    <x-dropdown align="right" width="48" class="relative z-50">
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

                            <!-- Requests Link -->
                            @auth
                            <x-dropdown-link href="{{ route('requests.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 inline mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m15 11-1 9"/>
                                    <path d="m19 11-4-7"/>
                                    <path d="M2 11h20"/>
                                    <path d="m3.5 11 1.6 7.4a2 2 0 0 0 2 1.6h9.8a2 2 0 0 0 2-1.6l1.7-7.4"/>
                                    <path d="M4.5 15.5h15"/>
                                    <path d="m5 11 4-7"/>
                                    <path d="m9 11 1 9"/>
                                </svg>
                                <span>{{ __('Requests') }}</span>
                            </x-dropdown-link>

                            <div class="border-t border-gray-200"></div>
                            @endauth

                            <!-- Orders Link -->
                            @role('Citizen')
                            <x-dropdown-link href="{{ route('orders.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 inline mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22v-9"/>
                                    <path d="M15.17 2.21a1.67 1.67 0 0 1 1.63 0L21 4.57a1.93 1.93 0 0 1 0 3.36L8.82 14.79a1.655 1.655 0 0 1-1.64 0L3 12.43a1.93 1.93 0 0 1 0-3.36z"/>
                                    <path d="M20 13v3.87a2.06 2.06 0 0 1-1.11 1.83l-6 3.08a1.93 1.93 0 0 1-1.78 0l-6-3.08A2.06 2.06 0 0 1 4 16.87V13"/>
                                    <path d="M21 12.43a1.93 1.93 0 0 0 0-3.36L8.83 2.2a1.64 1.64 0 0 0-1.63 0L3 4.57a1.93 1.93 0 0 0 0 3.36l12.18 6.86a1.636 1.636 0 0 0 1.63 0z"/>
                                </svg>
                                <span>{{ __('Orders') }}</span>
                            </x-dropdown-link>

                            <div class="border-t border-gray-200"></div>
                            @endrole

                            @role('Admin') 
                                @php
                                    $pendingReviews = \App\Models\Review::where('status', 'suspended')->count();
                                @endphp

                            <x-dropdown-link href="{{ route('admin.dashboard') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 inline mr-2" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M3 12h4v8H3v-8Zm7-5h4v13h-4V7Zm7-3h4v16h-4V4Z"/>
                                </svg>
                                <span>{{ __('Dashboard') }}</span>
                                @if($pendingReviews > 0)
                                    <span class="ml-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                        {{ $pendingReviews }}
                                    </span>
                                @endif
                            </x-dropdown-link>

                            <div class="border-t border-gray-200"></div>
                            @endrole

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
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                                <polyline points="10 17 15 12 10 7"/>
                                <line x1="15" x2="3" y1="12" y2="12"/>
                            </svg>
                        </x-nav-link>

                        <x-nav-link href="{{ route('register') }}" class="flex items-center space-x-2">
                            <span>{{ __('Register') }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path d="M12 20h9"/>
                                <path d="M16.376 3.622a1 1 0 0 1 3.002 3.002L7.368 18.635a2 2 0 0 1-.855.506l-2.872.838a.5.5 0 0 1-.62-.62l.838-2.872a2 2 0 0 1 .506-.854z"/>
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
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="18" height="18" x="3" y="3" rx="2"/>
                    <path d="M7 7v10"/>
                    <path d="M11 7v10"/>
                    <path d="m15 7 2 10"/>
                </svg>
                <span>{{ __('Books') }}</span>
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('authors.index') }}" :active="request()->routeIs('authors.*')">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m21 17-2.156-1.868A.5.5 0 0 0 18 15.5v.5a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1c0-2.545-3.991-3.97-8.5-4a1 1 0 0 0 0 5c4.153 0 4.745-11.295 5.708-13.5a2.5 2.5 0 1 1 3.31 3.284"/>
                    <path d="M3 21h18"/>
                </svg>
                <span>{{ __('Authors') }}</span>
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('publishers.index') }}" :active="request()->routeIs('publishers.*')">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                    <path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"/>
                    <rect x="6" y="14" width="12" height="8" rx="1"/>
                </svg>
                <span>{{ __('Publishers') }}</span>
            </x-responsive-nav-link>
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

                @role('Citizen')
                    <x-responsive-nav-link href="#" class="relative cursor-pointer">
                        <button id="cartIconBtn" class="flex items-center relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500 inline"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 3h2l3 10h11l3-7H6"></path>
                                <circle cx="9" cy="20" r="2"></circle>
                                <circle cx="18" cy="20" r="2"></circle>
                            </svg>
                            <span class="ml-2 text-blue-500">{{ __('Cart') }}</span>
                        </button>
                    </x-responsive-nav-link>
                @endrole

                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500 inline" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.67 0 8 1.34 8 4v2H4v-2c0-2.66 5.33-4 8-4zm0-2a4 4 0 100-8 4 4 0 000 8z" />
                    </svg>
                    <span>{{ __('Profile') }}</span>
                </x-responsive-nav-link>

                <!-- Requests Link -->
                @auth
                <x-responsive-nav-link href="{{ route('requests.index') }}" :active="request()->routeIs('requests.*')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m15 11-1 9"/>
                        <path d="m19 11-4-7"/>
                        <path d="M2 11h20"/>
                        <path d="m3.5 11 1.6 7.4a2 2 0 0 0 2 1.6h9.8a2 2 0 0 0 2-1.6l1.7-7.4"/>
                        <path d="M4.5 15.5h15"/>
                        <path d="m5 11 4-7"/>
                        <path d="m9 11 1 9"/>
                    </svg>
                    <span>{{ __('Requests') }}</span>
                </x-responsive-nav-link>
                @endauth

                <!-- Orders Link -->
                @role('Citizen')
                <x-responsive-nav-link href="{{ route('orders.index') }}" :active="request()->routeIs('orders.*')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22v-9"/>
                        <path d="M15.17 2.21a1.67 1.67 0 0 1 1.63 0L21 4.57a1.93 1.93 0 0 1 0 3.36L8.82 14.79a1.655 1.655 0 0 1-1.64 0L3 12.43a1.93 1.93 0 0 1 0-3.36z"/>
                        <path d="M20 13v3.87a2.06 2.06 0 0 1-1.11 1.83l-6 3.08a1.93 1.93 0 0 1-1.78 0l-6-3.08A2.06 2.06 0 0 1 4 16.87V13"/>
                        <path d="M21 12.43a1.93 1.93 0 0 0 0-3.36L8.83 2.2a1.64 1.64 0 0 0-1.63 0L3 4.57a1.93 1.93 0 0 0 0 3.36l12.18 6.86a1.636 1.636 0 0 0 1.63 0z"/>
                    </svg>
                    <span>{{ __('Orders') }}</span>
                </x-responsive-nav-link>
                @endrole

                @role('Admin') 
                <x-responsive-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500 inline" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 13h4v8H3v-8Zm7-5h4v13h-4V8Zm7-3h4v16h-4V5Z"/>
                    </svg>
                    <span>{{ __('Dashboard') }}</span>
                    @if($pendingReviews > 0)
                        <span class="ml-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                            {{ $pendingReviews }}
                        </span>
                    @endif
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
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link href="{{ route('login') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500 inline" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                            <polyline points="10 17 15 12 10 7"/>
                            <line x1="15" x2="3" y1="12" y2="12"/>
                        </svg>
                        <span>Login</span>
                    </x-responsive-nav-link>
                    
                    <x-responsive-nav-link href="{{ route('register') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500 inline" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M12 20h9"/>
                            <path d="M16.376 3.622a1 1 0 0 1 3.002 3.002L7.368 18.635a2 2 0 0 1-.855.506l-2.872.838a.5.5 0 0 1-.62-.62l.838-2.872a2 2 0 0 1 .506-.854z"/>
                        </svg>
                        <span>Register</span>
                    </x-responsive-nav-link>
                </div>
            @endguest
        </div>
    </div>
</nav>
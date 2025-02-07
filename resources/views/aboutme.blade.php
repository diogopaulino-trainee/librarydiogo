<x-app-layout>
    <x-slot name="header">
        <h2>{{ __('About Me') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 300)" x-show="show" x-transition class="bg-white shadow-md rounded-lg p-6 border border-blue-500">
                <div class="mb-4 text-center">
                    <div class="w-48 h-48 mx-auto rounded-full p-1 bg-gradient-to-r from-blue-500 to-purple-500 pulse-animation">
                        <img src="{{ asset('images/profile_picture.png') }}" alt="Profile Picture" class="w-full h-full rounded-full object-cover shadow-lg">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 text-gray-800 text-center">
                    <div><strong>Name:</strong> Diogo Paulino</div>
                    <div><strong>Role:</strong> Web Developer</div>
                    <div><strong>About Me:</strong> Passionate about coding, technology, and continuous learning. Always eager to take on new challenges and improve my skills.</div>
                </div>

                <div class="mt-6 flex justify-center gap-4">
                    <a href="https://www.linkedin.com/in/diogo-paulino/" target="_blank" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-900 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="h-5 w-5">
                            <path d="M4.98 3.5C4.98 4.88 3.86 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1s2.48 1.12 2.48 2.5zM0 8h5v16H0V8zm7.5 0h4.8v2.3h.07c.67-1.26 2.3-2.6 4.73-2.6 5.05 0 6 3.32 6 7.63V24h-5V15.1c0-2.1-.04-4.78-2.9-4.78-2.9 0-3.34 2.26-3.34 4.62V24h-5V8z"/>
                        </svg>
                        LinkedIn
                    </a>
                    <a href="https://github.com/diogopaulin0" target="_blank" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-black flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="h-5 w-5">
                            <path d="M12 0C5.37 0 0 5.37 0 12c0 5.3 3.44 9.8 8.21 11.38.6.1.82-.26.82-.58v-2.02c-3.34.72-4.04-1.61-4.04-1.61-.55-1.4-1.34-1.77-1.34-1.77-1.1-.76.08-.75.08-.75 1.22.08 1.86 1.25 1.86 1.25 1.08 1.85 2.84 1.32 3.53 1.01.1-.78.42-1.32.76-1.63-2.67-.3-5.47-1.34-5.47-5.97 0-1.32.47-2.4 1.24-3.24-.12-.3-.54-1.52.12-3.17 0 0 1.01-.32 3.3 1.23a11.5 11.5 0 0 1 6 0C17.9 6.73 18.91 7.05 18.91 7.05c.66 1.65.24 2.87.12 3.17.77.84 1.24 1.92 1.24 3.24 0 4.64-2.8 5.66-5.47 5.96.43.37.81 1.1.81 2.22v3.29c0 .32.22.69.83.58C20.57 21.8 24 17.3 24 12 24 5.37 18.63 0 12 0z"/>
                        </svg>
                        GitHub
                    </a>
                </div>
                <div class="mt-6 text-center text-gray-600 italic">
                    "The only way to do great work is to love what you do." — Steve Jobs
                </div>
            </div>
        </div>
    </div>
    
    <style>
        @keyframes pulse-scale {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .pulse-animation {
            animation: pulse-scale 2s infinite ease-in-out;
        }
    </style>
</x-app-layout>

<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-gray-200 via-blue-300 to-gray-400">
    <div class="mb-4 animate-bounce">
        {{ $logo }}
    </div>

    <div class="w-full sm:max-w-md mt-6 p-6 bg-white border-4 border-blue-600 shadow-2xl rounded-xl 
                hover:shadow-blue-500 transition-shadow duration-500 ease-in-out 
                ring-2 ring-transparent hover:ring-blue-500 hover:ring-offset-2 animate-pulse hover:animate-none">
        
        <div class="p-4 bg-gradient-to-r from-blue-200 to-blue-100 shadow-inner rounded-t-xl">
            <div class="space-y-4 text-gray-800">
                {{ $slot }}
            </div>
        </div>

        @isset($actions)
            <div class="flex items-center justify-end p-4 bg-blue-300 text-end shadow-inner rounded-b-xl">
                {{ $actions }}
            </div>
        @endisset
    </div>
</div>

<div class="md:col-span-1 flex justify-between items-center bg-blue-50 p-4 rounded-lg shadow-md border-4 border-blue-500 animate-pulse">
    <div class="px-4 sm:px-0">
        <h3 class="text-lg font-semibold text-blue-700">{{ $title }}</h3>

        <p class="mt-1 text-sm text-blue-500">
            {{ $description }}
        </p>
    </div>

    <div class="px-4 sm:px-0">
        {{ $aside ?? '' }}
    </div>
</div>

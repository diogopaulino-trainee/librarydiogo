<div {{ $attributes->merge(['class' => 'md:grid md:grid-cols-3 md:gap-6']) }}>
    <x-section-title>
        <x-slot name="title">{{ $title }}</x-slot>
        <x-slot name="description">{{ $description }}</x-slot>
    </x-section-title>

    <div class="mt-5 md:mt-0 md:col-span-2">
        <div class="px-4 py-5 sm:p-6 bg-white shadow-md rounded-lg border-4 border-blue-400">
            <div class="p-4 bg-blue-50 shadow-inner rounded-t-lg">
                {{ $content }}
            </div>
        </div>
    </div>
</div>

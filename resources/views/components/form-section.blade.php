@props(['submit'])

<div {{ $attributes->merge(['class' => 'md:grid md:grid-cols-3 md:gap-6']) }}>
    <x-section-title>
        <x-slot name="title">{{ $title }}</x-slot>
        <x-slot name="description">{{ $description }}</x-slot>
    </x-section-title>

    <div class="mt-5 md:mt-0 md:col-span-2">
        <form wire:submit="{{ $submit }}" class="bg-white shadow-md rounded-lg p-6 border-4 border-blue-400">
            <div class="p-4 bg-blue-50 shadow-inner rounded-t-lg {{ isset($actions) ? '' : 'rounded-b-lg' }}">
                <div class="grid grid-cols-6 gap-6">
                    {{ $form }}
                </div>
            </div>

            @if (isset($actions))
                <div class="flex items-center justify-end p-4 bg-blue-100 text-end shadow-inner rounded-b-lg">
                    {{ $actions }}
                </div>
            @endif
        </form>
    </div>
</div>

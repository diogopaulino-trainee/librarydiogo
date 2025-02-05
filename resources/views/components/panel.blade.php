@props(['class' => ''])

@php
    $classes = 'p-12 bg-white rounded-xl border border-transparent hover:border-blue-800 shadow-lg group transition-all duration-300 overflow-visible flex flex-col items-center justify-center space-y-4 min-h-[320px]';
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>

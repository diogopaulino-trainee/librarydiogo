<style>
    @keyframes wiggle {
        0% { transform: rotate(0deg); }
        25% { transform: rotate(0.5deg); }
        50% { transform: rotate(-0.5deg); }
        75% { transform: rotate(0.5deg); }
        100% { transform: rotate(0deg); }
    }

    .hover-wiggle:hover {
        animation: wiggle 0.3s ease-in-out infinite;
    }
</style>

@props(['class' => ''])

@php
    $classes = 'p-12 bg-white rounded-xl border border-transparent hover:border-blue-800 shadow-lg group transition-all duration-300 overflow-visible flex flex-col items-center justify-center space-y-4 min-h-[320px] min-w-[320px] hover-wiggle';
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>

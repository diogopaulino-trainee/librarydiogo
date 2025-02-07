@props(['active'])

@php
$classes = ($active ?? false)
    ? 'inline-flex items-center px-1 pt-1 border-b-2 border-blue-700 text-2xl font-medium leading-6 text-blue-700 focus:outline-none focus:border-blue-500 transition duration-150 ease-in-out font-sans tracking-wide'
    : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-2xl font-bold leading-6 text-blue-500 hover:text-blue-700 hover:border-blue-500 focus:outline-none focus:text-blue-700 focus:border-blue-500 transition duration-150 ease-in-out font-sans tracking-wide';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

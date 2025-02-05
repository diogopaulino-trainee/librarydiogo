@props(['active'])

@php
$classes = ($active ?? false)
    ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-blue-700 text-start text-base font-medium text-blue-700 bg-blue-50 focus:outline-none focus:text-blue-800 focus:bg-blue-100 focus:border-blue-700 transition duration-150 ease-in-out font-sans tracking-wide'
    : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-blue-500 hover:text-blue-700 hover:bg-blue-50 hover:border-blue-500 focus:outline-none focus:text-blue-700 focus:bg-blue-50 focus:border-blue-500 transition duration-150 ease-in-out font-sans tracking-wide';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

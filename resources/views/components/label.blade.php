@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-lg font-extrabold text-gray-700']) }}>
    {{ $value ?? $slot }}
</label>

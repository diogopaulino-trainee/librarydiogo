@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-xl font-extrabold text-gray-700']) }}>
    {{ $value ?? $slot }}
</label>

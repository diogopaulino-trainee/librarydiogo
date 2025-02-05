<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-primary bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-md shadow-md transition ease-in-out duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50']) }}>
    {{ $slot }}
</button>

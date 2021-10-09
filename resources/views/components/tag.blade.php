<button {{ $attributes->merge(['type' => 'button', 'class' => 'px-4 py-2 bg-gray-400 text-white rounded']) }}>
    {{ $slot }}
</button>
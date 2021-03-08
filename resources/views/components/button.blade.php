<button {{ $attributes->merge(['type' => 'submit', 'class' => 'py-2 default-button bg-yellow-600 hover:bg-yellow-500 text-white']) }}>
    {{ $slot }}
</button>

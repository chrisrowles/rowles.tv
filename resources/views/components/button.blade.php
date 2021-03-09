<button {{ $attributes->merge(['type' => 'submit', 'class' => 'py-2 default-button bg-purple-600 hover:bg-purple-500 text-white']) }}>
    {{ $slot }}
</button>

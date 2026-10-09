<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-primary px-6 py-3 text-sm font-bold tracking-widest']) }}>
    {{ $slot }}
</button>

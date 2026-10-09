<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-surface border border-line-strong rounded-md font-semibold text-xs text-fg-soft uppercase tracking-widest shadow-sm hover:bg-surface-muted focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-surface disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>

{{-- Icons are swapped with the `dark` class (not Alpine) so they are correct before JS boots. --}}
<button
    type="button"
    x-data
    @click="$store.theme.toggle()"
    :aria-pressed="$store.theme.dark.toString()"
    :title="$store.theme.dark ? 'Switch to light mode' : 'Switch to dark mode'"
    aria-label="Toggle dark mode"
    {{ $attributes->merge(['class' => 'inline-flex h-9 w-9 items-center justify-center rounded-full text-fg-muted transition hover:bg-surface-muted hover:text-fg focus:outline-none focus:ring-2 focus:ring-primary']) }}
>
    <x-icon name="moon" class="h-5 w-5 dark:hidden" />
    <x-icon name="sun" class="hidden h-5 w-5 dark:block" />
</button>

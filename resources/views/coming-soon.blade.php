<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-fg">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="flex flex-col items-center gap-3 rounded-xl border border-dashed border-line-strong bg-surface px-6 py-16 text-center">
            <x-icon name="cube" class="h-10 w-10 text-fg-subtle" />
            <h3 class="text-lg font-semibold text-fg-soft">{{ $title }} is coming soon</h3>
            <p class="max-w-sm text-sm text-fg-muted">This module is being built in the next phase of the POS system.</p>
        </div>
    </div>
</x-app-layout>

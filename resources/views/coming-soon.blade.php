<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-fg">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="page-narrow">
        <div class="flex flex-col items-center gap-4 rounded-2xl border border-dashed border-line-strong bg-surface px-6 py-24 text-center">
            <x-icon name="cube" class="h-14 w-14 text-fg-subtle" />
            <h3 class="text-2xl font-semibold text-fg-soft">{{ $title }} is coming soon</h3>
            <p class="max-w-md text-base text-fg-muted">This module is being built in the next phase of the POS system.</p>
        </div>
    </div>
</x-app-layout>

@props(['title', 'subtitle' => null])

{{-- Title row above page content; the slot holds the page's actions (buttons, toggles). --}}
<div {{ $attributes->merge(['class' => 'flex flex-wrap items-center justify-between gap-4']) }}>
    <div class="min-w-0">
        <h3 class="text-lg font-semibold text-fg">{{ $title }}</h3>
        @if ($subtitle)
            <p class="text-sm text-fg-muted">{{ $subtitle }}</p>
        @endif
    </div>

    @if ($slot->isNotEmpty())
        <div class="flex flex-wrap items-center gap-3">
            {{ $slot }}
        </div>
    @endif
</div>

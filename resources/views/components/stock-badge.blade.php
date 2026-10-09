@props(['product'])

@php
    $status = $product->stockStatus();

    // Full class names kept literal so Tailwind's scanner picks them up.
    $tones = [
        'success' => ['badge' => 'bg-success/10 text-success ring-success/20', 'dot' => 'bg-success'],
        'warning' => ['badge' => 'bg-warning/10 text-warning ring-warning/20', 'dot' => 'bg-warning'],
        'danger' => ['badge' => 'bg-danger/10 text-danger ring-danger/20', 'dot' => 'bg-danger'],
    ][$status->tone()];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset ' . $tones['badge']]) }}>
    <span class="h-1.5 w-1.5 rounded-full {{ $tones['dot'] }}"></span>
    {{ $slot->isEmpty() ? $status->label() : $slot }}
</span>

@php
    /** @var \App\Models\Phone $product */
    $status = $product->stockStatus();

    // Full class names kept literal so Tailwind's scanner picks them up.
    $tones = [
        'success' => ['badge' => 'bg-success/10 text-success ring-success/20', 'bar' => 'bg-success'],
        'warning' => ['badge' => 'bg-warning/10 text-warning ring-warning/20', 'bar' => 'bg-warning'],
        'danger' => ['badge' => 'bg-danger/10 text-danger ring-danger/20', 'bar' => 'bg-danger'],
    ];
    $tone = $tones[$status->tone()];

    // The bar spans 0 → 2× reorder level, so the reorder marker sits at the midpoint.
    $barMax = max($product->reorder_level * 2, 1);
    $fill = min(100, (int) round($product->stock_quantity / $barMax * 100));

    $category = $product->category
        ? ($product->category->parent ? $product->category->parent->name . ' / ' : '') . $product->category->name
        : 'Uncategorized';

    $lastMovement = $product->stockMovements->first();
@endphp

<article class="card group flex flex-col overflow-hidden transition duration-200 hover:-translate-y-0.5 hover:shadow-lg">
    <div class="relative flex h-40 items-center justify-center bg-surface-muted p-4">
        <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}" loading="lazy"
            class="h-full w-full object-contain transition duration-300 group-hover:scale-105">

        <span class="absolute left-3 top-3 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset backdrop-blur {{ $tone['badge'] }}">
            <span class="h-1.5 w-1.5 rounded-full {{ $tone['bar'] }}"></span>
            {{ $status->label() }}
        </span>
    </div>

    <div class="flex flex-1 flex-col p-5">
        <p class="truncate text-xs font-medium uppercase tracking-wide text-fg-subtle">{{ $category }}</p>
        <h3 class="mt-1 truncate text-base font-semibold text-fg" title="{{ $product->name }}">{{ $product->name }}</h3>

        <div class="mt-4 flex items-end justify-between gap-3">
            <div>
                <div class="text-3xl font-bold tabular-nums leading-none text-fg">{{ number_format($product->stock_quantity) }}</div>
                <div class="mt-1 text-xs text-fg-muted">units on hand</div>
            </div>
            <div class="text-right text-xs text-fg-muted">Reorder at <span class="font-semibold text-fg-soft">{{ $product->reorder_level }}</span></div>
        </div>

        <div class="relative mt-3 h-1.5 overflow-hidden rounded-full bg-line" role="presentation">
            <div class="h-full rounded-full {{ $tone['bar'] }}" style="width: {{ $fill }}%"></div>
            <div class="absolute inset-y-0 left-1/2 w-px bg-fg-subtle/60" title="Reorder level"></div>
        </div>

        <dl class="mt-4 grid grid-cols-3 gap-2 rounded-lg bg-surface-muted p-3 text-xs">
            <div>
                <dt class="text-fg-subtle">Cost</dt>
                <dd class="mt-0.5 font-semibold tabular-nums text-fg-soft">{{ $product->cost_price !== null ? 'Rs ' . number_format($product->cost_price) : '—' }}</dd>
            </div>
            <div>
                <dt class="text-fg-subtle">Sell</dt>
                <dd class="mt-0.5 font-semibold tabular-nums text-fg-soft">Rs {{ number_format($product->price) }}</dd>
            </div>
            <div>
                <dt class="text-fg-subtle">Value</dt>
                <dd class="mt-0.5 font-semibold tabular-nums text-fg-soft">Rs {{ number_format($product->stock_quantity * ($product->cost_price ?? 0)) }}</dd>
            </div>
        </dl>

        <p class="mt-4 text-xs text-fg-subtle">
            @if ($lastMovement)
                Updated {{ $lastMovement->created_at->diffForHumans() }}{{ $lastMovement->user ? ' by ' . $lastMovement->user->name : '' }}
            @else
                No stock movements yet
            @endif
        </p>

        <div class="mt-auto flex items-center gap-2 pt-4">
            <button type="button" x-on:click="openAdjust({{ $product->id }}, 'out')" title="Stock out"
                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-line-strong text-lg font-semibold text-fg-muted transition hover:border-danger hover:text-danger disabled:cursor-not-allowed disabled:opacity-40"
                @disabled($product->stock_quantity <= 0)>−</button>
            <button type="button" x-on:click="openAdjust({{ $product->id }}, 'in')" title="Stock in"
                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-line-strong text-lg font-semibold text-fg-muted transition hover:border-success hover:text-success">+</button>
            <button type="button" x-on:click="openHistory({{ $product->id }})"
                class="inline-flex h-9 items-center rounded-lg px-3 text-sm font-medium text-fg-muted transition hover:bg-surface-muted hover:text-fg">History</button>
            <button type="button" x-on:click="openAdjust({{ $product->id }}, 'adjustment')"
                class="btn-primary ml-auto h-9 px-3 text-sm">Adjust</button>
        </div>
    </div>
</article>

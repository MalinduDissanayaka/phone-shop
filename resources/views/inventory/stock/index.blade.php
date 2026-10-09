@php
    $statusOptions = ['' => 'All'] + collect(\App\Enums\StockStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()])->all();
    $activeStatus = $filters['status'] ?? '';

    // Client-side data for the adjust / history modals, keyed by product id.
    $productData = $products->getCollection()->mapWithKeys(fn ($p) => [$p->id => [
        'id' => $p->id,
        'name' => $p->name,
        'stock' => $p->stock_quantity,
        'adjustUrl' => route('inventory.stock.adjust', $p),
        'history' => $p->stockMovements->map(fn ($m) => [
            'label' => $m->type->label(),
            'quantity' => $m->quantity,
            'balance' => $m->balance_after,
            'note' => $m->note,
            'user' => $m->user?->name,
            'when' => $m->created_at->diffForHumans(),
            'at' => $m->created_at->format('d M Y, H:i'),
        ])->values(),
    ]]);

    $hasAdjustErrors = $errors->stockAdjustment->isNotEmpty();
@endphp

<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold leading-tight text-fg">Product Stock</h2>
            <p class="hidden text-sm text-fg-muted sm:block">Track on-hand quantity for every product.</p>
        </div>
    </x-slot>

    <div
        class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8"
        x-data="{
            products: @js($productData),
            product: null,
            type: 'in',
            quantity: '',
            init() {
                @if ($hasAdjustErrors)
                    this.product = this.products[@js((int) old('product_id'))] ?? null;
                    this.type = @js(old('type', 'in'));
                    this.quantity = @js(old('quantity', ''));
                @endif
            },
            openAdjust(id, type) {
                this.product = this.products[id];
                this.type = type;
                this.quantity = type === 'adjustment' ? this.product.stock : '';
                this.$dispatch('open-modal', 'adjust-stock');
            },
            openHistory(id) {
                this.product = this.products[id];
                this.$dispatch('open-modal', 'stock-history');
            },
            get preview() {
                if (! this.product || this.quantity === '' || isNaN(this.quantity)) return null;
                const qty = parseInt(this.quantity, 10);
                if (this.type === 'in') return this.product.stock + qty;
                if (this.type === 'out') return this.product.stock - qty;
                return qty;
            },
        }"
    >
        @if (session('status'))
            <div class="alert-success">{{ session('status') }}</div>
        @endif

        {{-- Summary --}}
        <section class="grid grid-cols-2 gap-4 lg:grid-cols-5">
            <div class="card p-4">
                <div class="text-xs font-medium uppercase tracking-wide text-fg-subtle">Products</div>
                <div class="mt-2 text-2xl font-bold tabular-nums text-fg">{{ number_format($summary['products']) }}</div>
            </div>
            <div class="card p-4">
                <div class="text-xs font-medium uppercase tracking-wide text-fg-subtle">Units on hand</div>
                <div class="mt-2 text-2xl font-bold tabular-nums text-fg">{{ number_format($summary['units']) }}</div>
            </div>
            <div class="card col-span-2 p-4 lg:col-span-1">
                <div class="text-xs font-medium uppercase tracking-wide text-fg-subtle">Stock value (cost)</div>
                <div class="mt-2 text-2xl font-bold tabular-nums text-fg">Rs {{ number_format($summary['cost_value']) }}</div>
                <div class="mt-0.5 text-xs text-fg-muted">Retail Rs {{ number_format($summary['retail_value']) }}</div>
            </div>
            <a href="{{ route('inventory.stock', ['status' => 'low']) }}" class="card p-4 transition hover:ring-2 hover:ring-warning/40">
                <div class="text-xs font-medium uppercase tracking-wide text-fg-subtle">Low stock</div>
                <div class="mt-2 text-2xl font-bold tabular-nums text-warning">{{ number_format($summary['low']) }}</div>
            </a>
            <a href="{{ route('inventory.stock', ['status' => 'out']) }}" class="card p-4 transition hover:ring-2 hover:ring-danger/40">
                <div class="text-xs font-medium uppercase tracking-wide text-fg-subtle">Out of stock</div>
                <div class="mt-2 text-2xl font-bold tabular-nums text-danger">{{ number_format($summary['out']) }}</div>
            </a>
        </section>

        {{-- Filters (GET so results are bookmarkable and survive pagination) --}}
        <form method="GET" action="{{ route('inventory.stock') }}" class="card space-y-4 p-4">
            <div class="grid grid-cols-1 gap-3 md:grid-cols-[1fr_14rem_12rem]">
                <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search products…" class="field w-full" aria-label="Search products">

                <select name="category" class="field w-full" onchange="this.form.submit()" aria-label="Category">
                    <option value="">All categories</option>
                    @foreach ($mainCategories as $main)
                        <option value="{{ $main->id }}" @selected(($filters['category'] ?? null) == $main->id)>{{ $main->name }}</option>
                        @foreach ($main->children as $child)
                            <option value="{{ $child->id }}" @selected(($filters['category'] ?? null) == $child->id)>&nbsp;&nbsp;↳ {{ $child->name }}</option>
                        @endforeach
                    @endforeach
                </select>

                <select name="sort" class="field w-full" onchange="this.form.submit()" aria-label="Sort">
                    <option value="name" @selected(($filters['sort'] ?? 'name') === 'name')>Name (A–Z)</option>
                    <option value="stock_asc" @selected(($filters['sort'] ?? '') === 'stock_asc')>Stock: low to high</option>
                    <option value="stock_desc" @selected(($filters['sort'] ?? '') === 'stock_desc')>Stock: high to low</option>
                </select>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <input type="hidden" name="status" value="{{ $activeStatus }}">
                @foreach ($statusOptions as $value => $label)
                    <a href="{{ route('inventory.stock', array_filter(array_merge($filters, ['status' => $value]), fn ($v) => $v !== null && $v !== '')) }}"
                        @class([
                            'rounded-full px-3 py-1.5 text-sm font-medium transition',
                            'bg-gradient-to-r from-primary to-accent text-white shadow-sm' => $activeStatus === $value,
                            'bg-surface-muted text-fg-muted hover:text-fg' => $activeStatus !== $value,
                        ])>{{ $label }}</a>
                @endforeach

                @if (array_filter($filters))
                    <a href="{{ route('inventory.stock') }}" class="ml-auto text-sm font-medium text-link hover:text-link/80">Clear filters</a>
                @endif
            </div>
        </form>

        {{-- Product cards --}}
        @if ($products->isEmpty())
            <div class="rounded-xl border border-dashed border-line-strong bg-surface px-6 py-16 text-center">
                <x-icon name="cube" class="mx-auto h-10 w-10 text-fg-subtle" />
                <p class="mt-3 text-sm text-fg-muted">
                    {{ array_filter($filters) ? 'No products match these filters.' : 'No products yet. Add products first, then manage their stock here.' }}
                </p>
            </div>
        @else
            <section class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($products as $product)
                    @include('inventory.stock._card', ['product' => $product])
                @endforeach
            </section>

            @if ($products->hasPages())
                <nav class="flex items-center justify-between text-sm" aria-label="Pagination">
                    <p class="text-fg-muted">Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }}</p>
                    <div class="flex gap-2">
                        @if ($products->onFirstPage())
                            <span class="rounded-lg border border-line px-3 py-1.5 text-fg-subtle">Previous</span>
                        @else
                            <a href="{{ $products->previousPageUrl() }}" class="rounded-lg border border-line-strong px-3 py-1.5 text-fg-soft hover:bg-surface-muted">Previous</a>
                        @endif
                        @if ($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}" class="rounded-lg border border-line-strong px-3 py-1.5 text-fg-soft hover:bg-surface-muted">Next</a>
                        @else
                            <span class="rounded-lg border border-line px-3 py-1.5 text-fg-subtle">Next</span>
                        @endif
                    </div>
                </nav>
            @endif
        @endif

        {{-- Adjust stock modal --}}
        <x-modal name="adjust-stock" max-width="md" :show="$hasAdjustErrors" focusable>
            <form method="POST" :action="product?.adjustUrl" class="p-6">
                @csrf
                <input type="hidden" name="product_id" :value="product?.id">

                <h2 class="text-lg font-semibold text-fg">Adjust stock</h2>
                <p class="mt-1 text-sm text-fg-muted">
                    <span class="font-medium text-fg-soft" x-text="product?.name"></span>
                    · <span x-text="product?.stock"></span> on hand
                </p>

                <div class="mt-5 grid grid-cols-3 gap-2" role="radiogroup" aria-label="Movement type">
                    @foreach ($movementTypes as $movementType)
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="{{ $movementType->value }}" x-model="type" class="peer sr-only">
                            <span class="block rounded-lg border border-line-strong px-2 py-2 text-center text-sm font-medium text-fg-muted transition peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:text-link peer-focus-visible:ring-2 peer-focus-visible:ring-primary">
                                {{ $movementType->label() }}
                            </span>
                        </label>
                    @endforeach
                </div>

                <div class="mt-5">
                    <x-input-label for="quantity" x-text="type === 'adjustment' ? 'Counted quantity on hand' : 'Quantity'" />
                    <x-text-input id="quantity" name="quantity" type="number" min="0" step="1" class="mt-1 block w-full" x-model="quantity" required />
                    <x-input-error class="mt-2" :messages="$errors->stockAdjustment->get('quantity')" />
                    <x-input-error class="mt-2" :messages="$errors->stockAdjustment->get('type')" />
                </div>

                <div class="mt-4">
                    <x-input-label for="note" value="Note (optional)" />
                    <x-text-input id="note" name="note" type="text" maxlength="255" class="mt-1 block w-full" :value="old('note')" placeholder="e.g. Supplier delivery #1042" />
                    <x-input-error class="mt-2" :messages="$errors->stockAdjustment->get('note')" />
                </div>

                <div class="mt-5 flex items-center justify-between rounded-lg bg-surface-muted px-4 py-3 text-sm" x-show="preview !== null">
                    <span class="text-fg-muted">New balance</span>
                    <span class="text-lg font-bold tabular-nums" :class="preview < 0 ? 'text-danger' : 'text-fg'" x-text="preview < 0 ? 'Not enough stock' : preview"></span>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                    <x-primary-button x-bind:disabled="preview === null || preview < 0" class="disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:translate-y-0">Save</x-primary-button>
                </div>
            </form>
        </x-modal>

        {{-- Stock history modal --}}
        <x-modal name="stock-history" max-width="lg">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-fg">Stock history</h2>
                <p class="mt-1 text-sm text-fg-muted" x-text="product?.name"></p>

                <template x-if="product && product.history.length === 0">
                    <p class="mt-6 rounded-lg bg-surface-muted px-4 py-6 text-center text-sm text-fg-muted">No stock movements recorded yet.</p>
                </template>

                <ul class="mt-5 divide-y divide-line" x-show="product && product.history.length">
                    <template x-for="(entry, i) in product?.history ?? []" :key="i">
                        <li class="flex items-start justify-between gap-4 py-3">
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-fg" x-text="entry.label"></p>
                                <p class="truncate text-xs text-fg-muted" x-show="entry.note" x-text="entry.note"></p>
                                <p class="mt-0.5 text-xs text-fg-subtle">
                                    <span :title="entry.at" x-text="entry.when"></span><span x-show="entry.user" x-text="' · ' + entry.user"></span>
                                </p>
                            </div>
                            <div class="shrink-0 text-right">
                                <p class="text-sm font-semibold tabular-nums"
                                    :class="entry.quantity > 0 ? 'text-success' : (entry.quantity < 0 ? 'text-danger' : 'text-fg-muted')"
                                    x-text="(entry.quantity > 0 ? '+' : '') + entry.quantity"></p>
                                <p class="text-xs text-fg-subtle">Balance <span class="tabular-nums" x-text="entry.balance"></span></p>
                            </div>
                        </li>
                    </template>
                </ul>

                <p class="mt-4 text-xs text-fg-subtle">Showing the latest {{ $historyLimit }} movements.</p>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">Close</x-secondary-button>
                </div>
            </div>
        </x-modal>
    </div>
</x-app-layout>

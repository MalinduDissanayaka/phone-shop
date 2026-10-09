@php
    $categoryPath = fn ($product) => $product->category
        ? ($product->category->parent ? $product->category->parent->name . ' / ' : '') . $product->category->name
        : null;

    // Shared data for the "view product" modal, keyed by product id.
    $productData = $products->mapWithKeys(fn ($p) => [$p->id => [
        'name' => $p->name,
        'description' => $p->description,
        'costPrice' => $p->cost_price !== null ? number_format($p->cost_price) : null,
        'price' => number_format($p->price),
        'stock' => number_format($p->stock_quantity),
        'stockLabel' => $p->stockStatus()->label(),
        'category' => $categoryPath($p),
        'image' => asset('images/' . $p->image),
        'editUrl' => route('inventory.products.edit', $p),
    ]]);
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-fg">
            Add Product
        </h2>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8"
        x-data="viewMode('inventory.products')">
        <div x-data="{ products: @js($productData), viewingProduct: null, show(id) { this.viewingProduct = this.products[id]; this.$dispatch('open-modal', 'view-product'); } }"
            class="space-y-6">
            @if (session('status'))
                <div class="alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-fg">Products</h3>
                    <p class="text-sm text-fg-muted">{{ $products->count() }} {{ Str::plural('product', $products->count()) }} in inventory</p>
                </div>

                <div class="flex items-center gap-3">
                    <x-view-toggle />
                    <button type="button" x-on:click="$dispatch('open-modal', 'add-product')" class="btn-primary px-5 py-2.5 text-sm">
                        <x-icon name="plus" class="h-5 w-5" />
                        Add Product
                    </button>
                </div>
            </div>

            @if ($products->isEmpty())
                <div class="rounded-xl border border-dashed border-line-strong bg-surface px-6 py-20 text-center">
                    <x-icon name="cube" class="mx-auto h-12 w-12 text-fg-subtle" />
                    <p class="mt-4 text-base text-fg-muted">No products yet. Click <span class="font-semibold text-fg-soft">Add Product</span> to create your first one.</p>
                </div>
            @else
                {{-- Grid view --}}
                <section x-show="view === 'grid'" x-cloak class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach ($products as $product)
                        <article class="card group flex flex-col overflow-hidden transition duration-200 hover:-translate-y-0.5 hover:shadow-lg">
                            <button type="button" x-on:click="show({{ $product->id }})" class="relative flex h-52 items-center justify-center bg-surface-muted p-6" title="View {{ $product->name }}">
                                <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}" loading="lazy"
                                    class="h-full w-full object-contain transition duration-300 group-hover:scale-105">
                                <x-stock-badge :product="$product" class="absolute left-3 top-3 backdrop-blur" />
                            </button>

                            <div class="flex flex-1 flex-col p-5">
                                <p class="truncate text-xs font-medium uppercase tracking-wide text-fg-subtle">{{ $categoryPath($product) ?? 'Uncategorized' }}</p>
                                <h4 class="mt-1 truncate text-lg font-semibold text-fg" title="{{ $product->name }}">{{ $product->name }}</h4>
                                <p class="mt-1 line-clamp-2 text-sm text-fg-muted">{{ $product->description }}</p>

                                <dl class="mt-4 grid grid-cols-3 gap-2 rounded-lg bg-surface-muted p-3 text-xs">
                                    <div>
                                        <dt class="text-fg-subtle">Cost</dt>
                                        <dd class="mt-0.5 text-sm font-semibold tabular-nums text-fg-soft">{{ $product->cost_price !== null ? 'Rs ' . number_format($product->cost_price) : '—' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-fg-subtle">Sell</dt>
                                        <dd class="mt-0.5 text-sm font-semibold tabular-nums text-link">Rs {{ number_format($product->price) }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-fg-subtle">Stock</dt>
                                        <dd class="mt-0.5 text-sm font-semibold tabular-nums text-fg-soft">{{ number_format($product->stock_quantity) }}</dd>
                                    </div>
                                </dl>

                                <div class="mt-auto flex items-center gap-2 pt-4">
                                    <button type="button" x-on:click="show({{ $product->id }})" class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-lg border border-line-strong px-3 py-2 text-sm font-medium text-fg-soft transition hover:bg-surface-muted">
                                        <x-icon name="eye" class="h-4 w-4" /> View
                                    </button>
                                    <a href="{{ route('inventory.products.edit', $product) }}" class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-lg bg-link/10 px-3 py-2 text-sm font-medium text-link transition hover:bg-link/20">
                                        <x-icon name="pencil" class="h-4 w-4" /> Edit
                                    </a>
                                    <form action="{{ route('inventory.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-fg-subtle transition hover:bg-danger/10 hover:text-danger" title="Delete {{ $product->name }}">
                                            <x-icon name="trash" class="h-5 w-5" />
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </section>

                {{-- List view --}}
                <section x-show="view === 'list'" x-cloak class="card overflow-x-auto">
                    <table class="min-w-full divide-y divide-line">
                        <thead class="bg-surface-muted">
                            <tr>
                                <th class="table-head py-4">Product</th>
                                <th class="table-head py-4">Category</th>
                                <th class="table-head py-4">Cost Price</th>
                                <th class="table-head py-4">Sell Price</th>
                                <th class="table-head py-4">Stock</th>
                                <th class="table-head py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            @foreach ($products as $product)
                                <tr class="transition hover:bg-surface-muted/60">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}" loading="lazy" class="h-16 w-16 shrink-0 rounded-xl border border-line bg-surface-muted object-contain p-1.5">
                                            <div class="min-w-0">
                                                <div class="truncate text-base font-semibold text-fg">{{ $product->name }}</div>
                                                <div class="line-clamp-1 max-w-xs text-sm text-fg-muted">{{ $product->description }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-fg-muted">
                                        {{ $categoryPath($product) ?? '' }}
                                        @unless ($product->category)
                                            <span class="text-fg-subtle">Uncategorized</span>
                                        @endunless
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-base tabular-nums text-fg-soft">
                                        {{ $product->cost_price !== null ? 'Rs ' . number_format($product->cost_price) : '—' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-base font-semibold tabular-nums text-fg">Rs {{ number_format($product->price) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <x-stock-badge :product="$product">{{ number_format($product->stock_quantity) }}</x-stock-badge>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right">
                                        <div class="inline-flex items-center gap-1">
                                            <button type="button" x-on:click="show({{ $product->id }})" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-fg-muted transition hover:bg-surface-muted hover:text-fg" title="View">
                                                <x-icon name="eye" class="h-5 w-5" />
                                            </button>
                                            <a href="{{ route('inventory.products.edit', $product) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-link transition hover:bg-link/10" title="Edit">
                                                <x-icon name="pencil" class="h-5 w-5" />
                                            </a>
                                            <form action="{{ route('inventory.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-danger transition hover:bg-danger/10" title="Delete">
                                                    <x-icon name="trash" class="h-5 w-5" />
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>
            @endif

            {{-- Add Product Modal --}}
            <x-modal name="add-product" max-width="2xl" :show="$errors->any()">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-fg">Add Product</h2>

                    <form method="POST" action="{{ route('inventory.products.store') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                        @csrf
                        @include('inventory.products._form', ['product' => null])

                        <div class="flex justify-end gap-3">
                            <x-secondary-button type="button" x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                            <x-primary-button>Save Product</x-primary-button>
                        </div>
                    </form>
                </div>
            </x-modal>

            {{-- View Product Modal --}}
            <x-modal name="view-product" max-width="lg">
                <template x-if="viewingProduct">
                    <div>
                        <div class="flex h-64 items-center justify-center bg-surface-muted p-6">
                            <img :src="viewingProduct.image" :alt="viewingProduct.name" class="h-full w-full object-contain">
                        </div>

                        <div class="p-6">
                            <p class="text-xs font-medium uppercase tracking-wide text-fg-subtle" x-text="viewingProduct.category ?? 'Uncategorized'"></p>
                            <h2 class="mt-1 text-2xl font-semibold text-fg" x-text="viewingProduct.name"></h2>
                            <p class="mt-3 text-base text-fg-muted" x-text="viewingProduct.description"></p>

                            <div class="mt-5 grid grid-cols-3 gap-3">
                                <div class="rounded-lg bg-surface-muted p-3">
                                    <div class="text-xs uppercase tracking-wide text-fg-subtle">Cost Price</div>
                                    <div class="mt-1 font-semibold text-fg" x-text="viewingProduct.costPrice ? 'Rs ' + viewingProduct.costPrice : '—'"></div>
                                </div>
                                <div class="rounded-lg bg-surface-muted p-3">
                                    <div class="text-xs uppercase tracking-wide text-fg-subtle">Sell Price</div>
                                    <div class="mt-1 font-semibold text-link" x-text="'Rs ' + viewingProduct.price"></div>
                                </div>
                                <div class="rounded-lg bg-surface-muted p-3">
                                    <div class="text-xs uppercase tracking-wide text-fg-subtle">Stock</div>
                                    <div class="mt-1 font-semibold text-fg" x-text="viewingProduct.stock"></div>
                                    <div class="text-xs text-fg-muted" x-text="viewingProduct.stockLabel"></div>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end gap-3">
                                <x-secondary-button type="button" x-on:click="$dispatch('close')">Close</x-secondary-button>
                                <a :href="viewingProduct.editUrl" class="btn-primary px-4 py-2 text-sm">
                                    <x-icon name="pencil" class="h-4 w-4" /> Edit
                                </a>
                            </div>
                        </div>
                    </div>
                </template>
            </x-modal>
        </div>
    </div>
</x-app-layout>

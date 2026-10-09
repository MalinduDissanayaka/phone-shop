@php
    $subcategoryCount = $categories->sum(fn ($c) => $c->children->count());
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-fg">
            Product Category
        </h2>
    </x-slot>

    <div class="page" x-data="viewMode('inventory.categories')">
        @if (session('status'))
            <div class="alert-success">
                {{ session('status') }}
            </div>
        @endif

        <x-page-toolbar title="Categories">
            <x-slot:subtitle>{{ $categories->count() }} main · {{ $subcategoryCount }} subcategories</x-slot:subtitle>

            <x-view-toggle />
            <a href="{{ route('inventory.categories.create') }}" class="btn-primary px-5 py-2.5 text-sm">
                <x-icon name="plus" class="h-5 w-5" />
                Add Category
            </a>
        </x-page-toolbar>

        @if ($categories->isEmpty())
            <div class="rounded-xl border border-dashed border-line-strong bg-surface px-6 py-20 text-center">
                <x-icon name="tag" class="mx-auto h-12 w-12 text-fg-subtle" />
                <p class="mt-4 text-base text-fg-muted">No categories yet. Start by adding a main category like "Smartphones" or "Accessories".</p>
            </div>
        @else
            {{-- Grid view --}}
            <section x-show="view === 'grid'" x-cloak class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($categories as $category)
                    @php $totalProducts = $category->products_count + $category->children->sum('products_count'); @endphp

                    <article class="card flex flex-col overflow-hidden transition duration-200 hover:-translate-y-0.5 hover:shadow-lg">
                        <div class="flex items-start gap-4 p-6">
                            <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-primary to-accent text-white shadow-md shadow-primary/20">
                                <x-icon name="tag" class="h-7 w-7" />
                            </span>
                            <div class="min-w-0 flex-1">
                                <h4 class="truncate text-xl font-semibold text-fg" title="{{ $category->name }}">{{ $category->name }}</h4>
                                <div class="mt-2 flex flex-wrap gap-2 text-xs">
                                    <span class="rounded-full bg-link/10 px-2.5 py-1 font-medium text-link">{{ $totalProducts }} {{ Str::plural('product', $totalProducts) }}</span>
                                    <span class="rounded-full bg-surface-muted px-2.5 py-1 font-medium text-fg-muted">{{ $category->children->count() }} {{ Str::plural('subcategory', $category->children->count()) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex-1 border-t border-line px-6 py-4">
                            @if ($category->children->isEmpty())
                                <p class="py-2 text-sm text-fg-subtle">No subcategories yet.</p>
                            @else
                                <ul class="space-y-1">
                                    @foreach ($category->children as $child)
                                        <li class="group flex items-center justify-between gap-3 rounded-lg px-3 py-2.5 transition hover:bg-surface-muted">
                                            <span class="min-w-0 truncate text-base text-fg-soft">{{ $child->name }}</span>
                                            <span class="flex shrink-0 items-center gap-3 text-sm">
                                                <span class="text-xs text-fg-subtle">{{ $child->products_count }}</span>
                                                <a href="{{ route('inventory.categories.edit', $child) }}" class="text-fg-subtle transition hover:text-link" title="Edit {{ $child->name }}">
                                                    <x-icon name="pencil" class="h-4 w-4" />
                                                </a>
                                                <form action="{{ route('inventory.categories.destroy', $child) }}" method="POST" onsubmit="return confirm('Delete this subcategory?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-fg-subtle transition hover:text-danger" title="Delete {{ $child->name }}">
                                                        <x-icon name="trash" class="h-4 w-4" />
                                                    </button>
                                                </form>
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <div class="flex items-center justify-end gap-2 border-t border-line bg-surface-muted/60 px-6 py-3">
                            <a href="{{ route('inventory.categories.edit', $category) }}" class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-link transition hover:bg-link/10">
                                <x-icon name="pencil" class="h-4 w-4" /> Edit
                            </a>
                            <form action="{{ route('inventory.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category and all its subcategories?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-danger transition hover:bg-danger/10">
                                    <x-icon name="trash" class="h-4 w-4" /> Delete
                                </button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </section>

            {{-- List view --}}
            <section x-show="view === 'list'" x-cloak class="space-y-5">
                @foreach ($categories as $category)
                    @php $totalProducts = $category->products_count + $category->children->sum('products_count'); @endphp

                    <div class="card overflow-hidden">
                        <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-5">
                            <div class="flex min-w-0 items-center gap-4">
                                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-primary to-accent text-white">
                                    <x-icon name="tag" class="h-6 w-6" />
                                </span>
                                <div class="min-w-0">
                                    <span class="block truncate text-lg font-semibold text-fg">{{ $category->name }}</span>
                                    <span class="text-sm text-fg-muted">{{ $totalProducts }} {{ Str::plural('product', $totalProducts) }} · {{ $category->children->count() }} {{ Str::plural('subcategory', $category->children->count()) }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('inventory.categories.edit', $category) }}" class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-link transition hover:bg-link/10">
                                    <x-icon name="pencil" class="h-4 w-4" /> Edit
                                </a>
                                <form action="{{ route('inventory.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category and all its subcategories?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-danger transition hover:bg-danger/10">
                                        <x-icon name="trash" class="h-4 w-4" /> Delete
                                    </button>
                                </form>
                            </div>
                        </div>

                        @if ($category->children->isNotEmpty())
                            <ul class="divide-y divide-line border-t border-line bg-surface-muted">
                                @foreach ($category->children as $child)
                                    <li class="flex items-center justify-between gap-4 px-6 py-4 sm:pl-[5.5rem]">
                                        <span class="min-w-0 truncate text-base text-fg-soft">
                                            {{ $child->name }}
                                            <span class="ml-2 text-sm text-fg-subtle">{{ $child->products_count }} {{ Str::plural('product', $child->products_count) }}</span>
                                        </span>
                                        <div class="flex shrink-0 items-center gap-4 text-sm">
                                            <a href="{{ route('inventory.categories.edit', $child) }}" class="font-medium text-link hover:text-link/80">Edit</a>
                                            <form action="{{ route('inventory.categories.destroy', $child) }}" method="POST" onsubmit="return confirm('Delete this subcategory?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-medium text-danger hover:text-danger/80">Delete</button>
                                            </form>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach
            </section>
        @endif
    </div>
</x-app-layout>

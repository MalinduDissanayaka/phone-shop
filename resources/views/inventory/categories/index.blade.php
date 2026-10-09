<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-fg">
            Product Category
        </h2>
    </x-slot>

    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-6 alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div class="mb-4 flex justify-end">
            <a href="{{ route('inventory.categories.create') }}" class="btn-primary px-4 py-2 text-xs uppercase tracking-widest">
                <x-icon name="plus" class="h-4 w-4" />
                Add Category
            </a>
        </div>

        <div class="space-y-4">
            @forelse ($categories as $category)
                <div class="card overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4">
                        <div class="flex items-center gap-2">
                            <x-icon name="cube" class="h-5 w-5 text-fg-subtle" />
                            <span class="font-semibold text-fg">{{ $category->name }}</span>
                            <span class="rounded-full bg-surface-muted px-2 py-0.5 text-xs text-fg-muted">{{ $category->children->count() }} subcategories</span>
                        </div>
                        <div class="flex items-center gap-4 text-sm">
                            <a href="{{ route('inventory.categories.edit', $category) }}" class="font-medium text-link hover:text-link/80">Edit</a>
                            <form action="{{ route('inventory.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category and all its subcategories?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-medium text-danger hover:text-danger/80">Delete</button>
                            </form>
                        </div>
                    </div>

                    @if ($category->children->isNotEmpty())
                        <ul class="divide-y divide-line border-t border-line bg-surface-muted">
                            @foreach ($category->children as $child)
                                <li class="flex items-center justify-between px-6 py-3 pl-12 text-sm">
                                    <span class="text-fg-soft">{{ $child->name }}</span>
                                    <div class="flex items-center gap-4">
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
            @empty
                <div class="rounded-xl border border-dashed border-line-strong bg-surface px-6 py-16 text-center">
                    <p class="text-sm text-fg-muted">No categories yet. Start by adding a main category like "Smartphones" or "Accessories".</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Product Category
        </h2>
    </x-slot>

    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-6 rounded-lg bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                {{ session('status') }}
            </div>
        @endif

        <div class="mb-4 flex justify-end">
            <a href="{{ route('inventory.categories.create') }}" class="inline-flex items-center gap-2 rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700">
                <x-icon name="plus" class="h-4 w-4" />
                Add Category
            </a>
        </div>

        <div class="space-y-4">
            @forelse ($categories as $category)
                <div class="overflow-hidden rounded-xl bg-white shadow-sm">
                    <div class="flex items-center justify-between px-6 py-4">
                        <div class="flex items-center gap-2">
                            <x-icon name="cube" class="h-5 w-5 text-gray-400" />
                            <span class="font-semibold text-gray-900">{{ $category->name }}</span>
                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-500">{{ $category->children->count() }} subcategories</span>
                        </div>
                        <div class="flex items-center gap-4 text-sm">
                            <a href="{{ route('inventory.categories.edit', $category) }}" class="font-medium text-indigo-600 hover:text-indigo-500">Edit</a>
                            <form action="{{ route('inventory.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category and all its subcategories?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-medium text-red-600 hover:text-red-500">Delete</button>
                            </form>
                        </div>
                    </div>

                    @if ($category->children->isNotEmpty())
                        <ul class="divide-y divide-gray-100 border-t border-gray-100 bg-gray-50">
                            @foreach ($category->children as $child)
                                <li class="flex items-center justify-between px-6 py-3 pl-12 text-sm">
                                    <span class="text-gray-700">{{ $child->name }}</span>
                                    <div class="flex items-center gap-4">
                                        <a href="{{ route('inventory.categories.edit', $child) }}" class="font-medium text-indigo-600 hover:text-indigo-500">Edit</a>
                                        <form action="{{ route('inventory.categories.destroy', $child) }}" method="POST" onsubmit="return confirm('Delete this subcategory?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-red-600 hover:text-red-500">Delete</button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-gray-300 bg-white px-6 py-16 text-center">
                    <p class="text-sm text-gray-500">No categories yet. Start by adding a main category like "Smartphones" or "Accessories".</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-fg">
            Branch Setup
        </h2>
    </x-slot>

    <div class="page">
        @if (session('status'))
            <div class="alert-success">{{ session('status') }}</div>
        @endif

        <x-page-toolbar title="Branches" :subtitle="$branches->count() . ' ' . Str::plural('branch', $branches->count())">
            <a href="{{ route('settings.branches.create') }}" class="btn-primary px-5 py-2.5 text-sm">
                <x-icon name="plus" class="h-5 w-5" />
                Add Branch
            </a>
        </x-page-toolbar>

        <div class="card overflow-x-auto">
            <table class="min-w-full divide-y divide-line">
                <thead class="bg-surface-muted">
                    <tr>
                        <th class="table-head">Branch</th>
                        <th class="table-head">Address</th>
                        <th class="table-head">Phone</th>
                        <th class="table-head text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse ($branches as $branch)
                        <tr class="transition hover:bg-surface-muted/60">
                            <td class="table-cell whitespace-nowrap">
                                <div class="flex items-center gap-4">
                                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-link/10 text-link">
                                        <x-icon name="home" class="h-5 w-5" />
                                    </span>
                                    <span class="font-semibold text-fg">{{ $branch->name }}</span>
                                </div>
                            </td>
                            <td class="table-cell text-fg-muted">{{ $branch->address ?? '—' }}</td>
                            <td class="table-cell whitespace-nowrap tabular-nums text-fg-muted">{{ $branch->phone ?? '—' }}</td>
                            <td class="table-cell whitespace-nowrap text-right">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('settings.branches.edit', $branch) }}" class="btn-icon text-link hover:bg-link/10" title="Edit {{ $branch->name }}">
                                        <x-icon name="pencil" class="h-5 w-5" />
                                    </a>
                                    <form action="{{ route('settings.branches.destroy', $branch) }}" method="POST" onsubmit="return confirm('Delete this branch?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon text-danger hover:bg-danger/10" title="Delete {{ $branch->name }}">
                                            <x-icon name="trash" class="h-5 w-5" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center text-base text-fg-muted">No branches yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

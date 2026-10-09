<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-fg">
                Branch Setup
            </h2>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-6 alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div class="mb-4 flex justify-end">
            <a href="{{ route('settings.branches.create') }}" class="btn-primary px-4 py-2 text-xs uppercase tracking-widest">
                <x-icon name="plus" class="h-4 w-4" />
                Add Branch
            </a>
        </div>

        <div class="card overflow-hidden">
            <table class="min-w-full divide-y divide-line">
                <thead class="bg-surface-muted">
                    <tr>
                        <th class="table-head">Name</th>
                        <th class="table-head">Address</th>
                        <th class="table-head">Phone</th>
                        <th class="table-head text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse ($branches as $branch)
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-fg">{{ $branch->name }}</td>
                            <td class="px-6 py-4 text-sm text-fg-muted">{{ $branch->address ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-fg-muted">{{ $branch->phone ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                <a href="{{ route('settings.branches.edit', $branch) }}" class="font-medium text-link hover:text-link/80">Edit</a>
                                <form action="{{ route('settings.branches.destroy', $branch) }}" method="POST" class="ml-3 inline" onsubmit="return confirm('Delete this branch?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium text-danger hover:text-danger/80">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-sm text-fg-muted">No branches yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

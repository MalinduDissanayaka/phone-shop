<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-fg">
            User Role Creation
        </h2>
    </x-slot>

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-6 alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div class="mb-4 flex justify-end">
            <a href="{{ route('settings.roles.create') }}" class="btn-primary px-4 py-2 text-xs uppercase tracking-widest">
                <x-icon name="plus" class="h-4 w-4" />
                Add Role
            </a>
        </div>

        <div class="card overflow-hidden">
            <table class="min-w-full divide-y divide-line">
                <thead class="bg-surface-muted">
                    <tr>
                        <th class="table-head">Name</th>
                        <th class="table-head">Page Access</th>
                        <th class="table-head text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse ($roles as $role)
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-fg">
                                {{ $role->name }}
                                @if ($role->is_admin)
                                    <span class="ml-2 rounded-full bg-link/10 px-2 py-0.5 text-xs font-medium text-link">Full Access</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-fg-muted">
                                {{ $role->is_admin ? 'All pages' : (count($role->permissions ?? []) . ' page(s)') }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                @unless ($role->is_admin)
                                    <a href="{{ route('settings.roles.edit', $role) }}" class="font-medium text-link hover:text-link/80">Edit</a>
                                    <form action="{{ route('settings.roles.destroy', $role) }}" method="POST" class="ml-3 inline" onsubmit="return confirm('Delete this role?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-danger hover:text-danger/80">Delete</button>
                                    </form>
                                @else
                                    <span class="text-fg-subtle">—</span>
                                @endunless
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-sm text-fg-muted">No roles yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

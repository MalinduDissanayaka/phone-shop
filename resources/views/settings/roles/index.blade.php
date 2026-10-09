<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-fg">
            User Role Creation
        </h2>
    </x-slot>

    <div class="page">
        @if (session('status'))
            <div class="alert-success">{{ session('status') }}</div>
        @endif

        <x-page-toolbar title="Roles" :subtitle="$roles->count() . ' ' . Str::plural('role', $roles->count())">
            <a href="{{ route('settings.roles.create') }}" class="btn-primary px-5 py-2.5 text-sm">
                <x-icon name="plus" class="h-5 w-5" />
                Add Role
            </a>
        </x-page-toolbar>

        <div class="card overflow-x-auto">
            <table class="min-w-full divide-y divide-line">
                <thead class="bg-surface-muted">
                    <tr>
                        <th class="table-head">Role</th>
                        <th class="table-head">Page Access</th>
                        <th class="table-head text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse ($roles as $role)
                        @php $pageCount = count($role->permissions ?? []); @endphp
                        <tr class="transition hover:bg-surface-muted/60">
                            <td class="table-cell whitespace-nowrap">
                                <div class="flex items-center gap-4">
                                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-link/10 text-link">
                                        <x-icon name="users" class="h-5 w-5" />
                                    </span>
                                    <span class="font-semibold text-fg">{{ $role->name }}</span>
                                    @if ($role->is_admin)
                                        <span class="rounded-full bg-gradient-to-r from-primary to-accent px-2.5 py-1 text-xs font-semibold text-white">Full Access</span>
                                    @endif
                                </div>
                            </td>
                            <td class="table-cell text-fg-muted">
                                {{ $role->is_admin ? 'All pages' : $pageCount . ' ' . Str::plural('page', $pageCount) }}
                            </td>
                            <td class="table-cell whitespace-nowrap text-right">
                                @unless ($role->is_admin)
                                    <div class="inline-flex items-center gap-1">
                                        <a href="{{ route('settings.roles.edit', $role) }}" class="btn-icon text-link hover:bg-link/10" title="Edit {{ $role->name }}">
                                            <x-icon name="pencil" class="h-5 w-5" />
                                        </a>
                                        <form action="{{ route('settings.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Delete this role?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-icon text-danger hover:bg-danger/10" title="Delete {{ $role->name }}">
                                                <x-icon name="trash" class="h-5 w-5" />
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-sm text-fg-subtle">Protected</span>
                                @endunless
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-16 text-center text-base text-fg-muted">No roles yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

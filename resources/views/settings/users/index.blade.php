<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-fg">
            User Creation
        </h2>
    </x-slot>

    <div class="page">
        @if (session('status'))
            <div class="alert-success">{{ session('status') }}</div>
        @endif

        <x-page-toolbar title="Users" :subtitle="$users->count() . ' ' . Str::plural('user', $users->count())">
            <a href="{{ route('settings.users.create') }}" class="btn-primary px-5 py-2.5 text-sm">
                <x-icon name="plus" class="h-5 w-5" />
                Add User
            </a>
        </x-page-toolbar>

        <div class="card overflow-x-auto">
            <table class="min-w-full divide-y divide-line">
                <thead class="bg-surface-muted">
                    <tr>
                        <th class="table-head">User</th>
                        <th class="table-head">Role</th>
                        <th class="table-head">Branch</th>
                        <th class="table-head text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse ($users as $user)
                        <tr class="transition hover:bg-surface-muted/60">
                            <td class="table-cell">
                                <div class="flex items-center gap-4">
                                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-primary to-accent text-base font-semibold text-white">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                    <div class="min-w-0">
                                        <div class="truncate font-semibold text-fg">
                                            {{ $user->name }}
                                            @if ($user->id === auth()->id())
                                                <span class="ml-1 text-xs font-medium text-fg-subtle">(you)</span>
                                            @endif
                                        </div>
                                        <div class="truncate text-sm text-fg-muted">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="table-cell whitespace-nowrap">
                                @if ($user->role)
                                    <span @class([
                                        'rounded-full px-2.5 py-1 text-sm font-medium',
                                        'bg-gradient-to-r from-primary to-accent text-white' => $user->role->is_admin,
                                        'bg-link/10 text-link' => ! $user->role->is_admin,
                                    ])>{{ $user->role->name }}</span>
                                @else
                                    <span class="text-fg-subtle">—</span>
                                @endif
                            </td>
                            <td class="table-cell whitespace-nowrap text-fg-muted">{{ $user->branch->name ?? '—' }}</td>
                            <td class="table-cell whitespace-nowrap text-right">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('settings.users.edit', $user) }}" class="btn-icon text-link hover:bg-link/10" title="Edit {{ $user->name }}">
                                        <x-icon name="pencil" class="h-5 w-5" />
                                    </a>
                                    @unless ($user->id === auth()->id())
                                        <form action="{{ route('settings.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-icon text-danger hover:bg-danger/10" title="Delete {{ $user->name }}">
                                                <x-icon name="trash" class="h-5 w-5" />
                                            </button>
                                        </form>
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center text-base text-fg-muted">No users yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

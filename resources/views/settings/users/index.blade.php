<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-fg">
            User Creation
        </h2>
    </x-slot>

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-6 alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div class="mb-4 flex justify-end">
            <a href="{{ route('settings.users.create') }}" class="btn-primary px-4 py-2 text-xs uppercase tracking-widest">
                <x-icon name="plus" class="h-4 w-4" />
                Add User
            </a>
        </div>

        <div class="card overflow-hidden">
            <table class="min-w-full divide-y divide-line">
                <thead class="bg-surface-muted">
                    <tr>
                        <th class="table-head">Name</th>
                        <th class="table-head">Email</th>
                        <th class="table-head">Role</th>
                        <th class="table-head">Branch</th>
                        <th class="table-head text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse ($users as $user)
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-fg">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-sm text-fg-muted">{{ $user->email }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-fg-muted">{{ $user->role->name ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-fg-muted">{{ $user->branch->name ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                <a href="{{ route('settings.users.edit', $user) }}" class="font-medium text-link hover:text-link/80">Edit</a>
                                @unless ($user->id === auth()->id())
                                    <form action="{{ route('settings.users.destroy', $user) }}" method="POST" class="ml-3 inline" onsubmit="return confirm('Delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-danger hover:text-danger/80">Delete</button>
                                    </form>
                                @endunless
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-fg-muted">No users yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

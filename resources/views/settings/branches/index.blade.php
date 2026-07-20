<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Branch Setup
            </h2>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-6 rounded-lg bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                {{ session('status') }}
            </div>
        @endif

        <div class="mb-4 flex justify-end">
            <a href="{{ route('settings.branches.create') }}" class="inline-flex items-center gap-2 rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700">
                <x-icon name="plus" class="h-4 w-4" />
                Add Branch
            </a>
        </div>

        <div class="overflow-hidden rounded-xl bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Phone</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse ($branches as $branch)
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">{{ $branch->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $branch->address ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $branch->phone ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                <a href="{{ route('settings.branches.edit', $branch) }}" class="font-medium text-indigo-600 hover:text-indigo-500">Edit</a>
                                <form action="{{ route('settings.branches.destroy', $branch) }}" method="POST" class="ml-3 inline" onsubmit="return confirm('Delete this branch?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 hover:text-red-500">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">No branches yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

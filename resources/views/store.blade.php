<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Logged in message -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
                <div>
                     @if(auth()->check() && auth()->user()->role === 'admin')
                    <button>
                        <a href="/admin/phones/create">Add Phone</a>
                    </button>
                    @endif
                </div>
            </div>

            <!-- Phone Shop Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">Phone Shop</h1>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                       
                     @foreach($phones as $phone)
<div class="border rounded-lg p-4 relative"
     x-data="{ open: false }">

    <img src="{{ asset('images/'.$phone->image) }}" width="150">
    <h3 class="font-bold mt-2">{{ $phone->name }}</h3>
    <p>{{ $phone->description }}</p>
    <p class="font-semibold">Rs {{ $phone->price }}</p>

    @if(auth()->check() && auth()->user()->role === 'admin')

        <!-- Edit Button -->
        <a href="{{ route('admin.phones.edit', $phone->id) }}"
           class="text-blue-600 mr-3">Edit</a>

        <!-- Delete Button -->
        <button 
            @click="open = true"
            class="text-red-600">
            Delete
        </button>

        <!-- Confirmation Modal -->
        <div x-show="open"
             class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
             x-transition>

            <div class="bg-white p-6 rounded-lg shadow-lg w-96">

                <h2 class="text-lg font-bold mb-4">
                    Confirm Delete
                </h2>

                <p class="mb-6">
                    Are you sure you want to delete this item?
                </p>

                <div class="flex justify-end space-x-3">

                    <!-- Cancel Button -->
                    <button 
                        @click="open = false"
                        class="px-4 py-2 bg-gray-300 rounded">
                        Cancel
                    </button>

                    <!-- Confirm Delete Form -->
                    <form action="{{ route('admin.phones.delete', $phone->id) }}"
                          method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded">
                            Yes, Delete
                        </button>
                    </form>

                </div>
            </div>
        </div>

    @endif

</div>
@endforeach


                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>

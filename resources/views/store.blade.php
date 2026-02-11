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
            </div>

            <!-- Phone Shop Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">Phone Shop</h1>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                       
                       @foreach($phones as $phone)
    <div>
        <img src="{{ asset('images/'.$phone->image) }}" width="150">
        <h3>{{ $phone->name }}</h3>
        <p>{{ $phone->description }}</p>
        <p>Rs {{ $phone->price }}</p>

        @if(auth()->check() && auth()->user()->role === 'admin')
            <a href="{{ route('admin.phones.edit', $phone->id) }}">Edit</a>

            <form action="{{ route('admin.phones.delete', $phone->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        @endif
    </div>
@endforeach

                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>

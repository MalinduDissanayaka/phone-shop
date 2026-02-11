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
                            <div class="border rounded-lg p-4">
                                <img 
                                    src="{{ asset('images/'.$phone->image) }}" 
                                    class="w-full h-48 object-cover mb-4"
                                >
                                <h3 class="font-semibold text-lg">{{ $phone->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $phone->description }}</p>
                                <p class="font-bold mt-2">Rs {{ $phone->price }}</p>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>

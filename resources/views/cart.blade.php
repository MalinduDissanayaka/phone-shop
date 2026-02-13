<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            My Cart
        </h2>
       
    </x-slot>

    <div class="p-6 max-w-6xl mx-auto">

        @if($cartItems->count() > 0)

            @foreach($cartItems as $item)
                <div class="border p-4 mb-4 flex justify-between items-center">

                    <div>
                        <h3 class="font-bold">{{ $item->phone->name }}</h3>
                        <p>Rs {{ $item->phone->price }}</p>
                        <p>Quantity: {{ $item->quantity }}</p>
                    </div>

                    <form action="{{ route('cart.remove', $item->id) }}"
                          method="POST">
                        @csrf
                        @method('DELETE')

                        <button class="bg-red-600 text-white px-3 py-1 rounded">
                            Remove
                        </button>
                    </form>

                </div>
            @endforeach

        @else
            <p>Your cart is empty.</p>
        @endif

    </div>
</x-app-layout>

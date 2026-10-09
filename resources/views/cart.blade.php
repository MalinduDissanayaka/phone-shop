<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-fg">
            My Cart
        </h2>

    </x-slot>

    <div class="p-6 max-w-6xl mx-auto">

        @if($cartItems->count() > 0)

            @foreach($cartItems as $item)
                <div class="card p-4 mb-4 flex justify-between items-center">

                    <div>
                        <h3 class="font-bold text-fg">{{ $item->phone->name }}</h3>
                        <p class="text-fg-muted">Rs {{ $item->phone->price }}</p>
                        <p class="text-fg-muted">Quantity: {{ $item->quantity }}</p>
                    </div>

                    <form action="{{ route('cart.remove', $item->id) }}"
                          method="POST">
                        @csrf
                        @method('DELETE')

                        <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-500">
                            Remove
                        </button>
                    </form>

                </div>
            @endforeach

        @else
            <p class="text-fg-muted">Your cart is empty.</p>
        @endif

    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Welcome back 👋</p>
                <h2 class="font-semibold text-3xl text-gray-900">
                    Phone Shop
                </h2>
            </div>

            {{-- Cart Button (Only User) --}}
            @if(auth()->check() && auth()->user()->role === 'user')
                <a href="{{ route('cart') }}"
                   class="relative inline-flex items-center gap-2 bg-indigo-600 text-white px-5 py-2.5 rounded-xl shadow hover:bg-indigo-500 transition">

                    🛒 Cart

                    @if(isset($cartCount) && $cartCount > 0)
                        <span class="absolute -top-2 -right-2 bg-rose-500 text-xs text-white w-6 h-6 flex items-center justify-center rounded-full">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-b from-slate-50 to-white min-h-screen">
        <div class="max-w-7xl mx-auto px-6 space-y-10">

            {{-- Hero Section --}}
            <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 text-black p-10 shadow-xl">
                <div class="max-w-3xl">
                    <h1 class="text-4xl font-bold mb-4 leading-tight">
                        Discover the Future of Smartphones
                    </h1>
                    <p class="text-black/90 text-lg">
                        Explore top-tier devices, compare performance, and experience innovation at its finest.
                    </p>
                </div>

                @if(auth()->user()->role === 'admin')
                    <div class="absolute top-6 right-6">
                        <a href="{{ route('admin.phones.create') }}"
                           class="bg-white text-indigo-600 px-5 py-2.5 rounded-xl font-semibold shadow-lg hover:-translate-y-1 transition">
                            + Add New Phone
                        </a>
                    </div>
                @endif
            </section>

            {{-- Search & Filter --}}
            <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex flex-col md:flex-row gap-4 items-center">
                    <input type="search"
                           placeholder="Search smartphones..."
                           class="flex-1 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">

                    <button class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:border-indigo-500 hover:text-indigo-600 transition">
                        Filter
                    </button>

                    <button class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:border-indigo-500 hover:text-indigo-600 transition">
                        Sort
                    </button>
                </div>
            </section>

            {{-- Product Grid --}}
            @if($phones->count() > 0)

                <section class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">

                    @foreach($phones as $phone)
                        <div x-data="{ open: false }"
                             class="group bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300 flex flex-col overflow-hidden">

                            {{-- Image --}}
                            <div class="bg-slate-50 p-8 flex justify-center">
                                <img src="{{ asset('images/'.$phone->image) }}"
                                     alt="{{ $phone->name }}"
                                     class="h-44 object-contain group-hover:scale-105 transition duration-300">
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 p-6 space-y-4">

                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 group-hover:text-indigo-600 transition">
                                        {{ $phone->name }}
                                    </h3>
                                    <p class="text-sm text-gray-500 line-clamp-3">
                                        {{ $phone->description }}
                                    </p>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="text-2xl font-bold text-indigo-600">
                                        Rs {{ number_format($phone->price) }}
                                    </span>
                                    <span class="text-xs text-emerald-500 font-medium">
                                        In Stock
                                    </span>
                                </div>

                            </div>

                            {{-- Actions --}}
                            <div class="p-6 pt-0 flex gap-3">

                                {{-- USER BUTTON --}}
                                @if(auth()->user()->role === 'user')
                                    <form action="{{ route('cart.add', $phone->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button class="w-full bg-indigo-600 text-black py-2.5 rounded-xl font-medium hover:bg-indigo-500 transition shadow">
                                            Add to Cart
                                        </button>
                                    </form>
                                @endif

                                {{-- ADMIN BUTTONS --}}
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.phones.edit', $phone->id) }}"
                                       class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">
                                        Edit
                                    </a>

                                    <button @click="open = true"
                                            class="text-sm font-semibold text-rose-600 hover:text-rose-500">
                                        Delete
                                    </button>

                                    {{-- Modal --}}
                                    <div x-show="open"
                                         x-transition
                                         class="fixed inset-0 z-50 flex items-center justify-center">

                                        <div class="absolute inset-0 bg-black/50"
                                             @click="open = false"></div>

                                        <div class="relative bg-white rounded-3xl shadow-2xl p-8 max-w-md w-full space-y-6">

                                            <h2 class="text-2xl font-semibold text-gray-900">
                                                Delete {{ $phone->name }}?
                                            </h2>

                                            <p class="text-gray-600">
                                                This action cannot be undone. Are you sure?
                                            </p>

                                            <div class="flex justify-end gap-3">
                                                <button @click="open = false"
                                                        class="px-4 py-2 rounded-xl border border-gray-200">
                                                    Cancel
                                                </button>

                                                <form action="{{ route('admin.phones.delete', $phone->id) }}"
                                                      method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="px-4 py-2 rounded-xl bg-rose-600 text-white hover:bg-rose-500">
                                                        Yes, Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endforeach

                </section>

            @else
                {{-- Empty State --}}
                <div class="text-center py-20">
                    <h3 class="text-2xl font-semibold text-gray-700 mb-2">
                        No Phones Available
                    </h3>
                    <p class="text-gray-500">
                        Please check back later.
                    </p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Coffee Shop</title>

    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-[#f8f6f2] text-stone-800">

    <header class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-6 py-5
                    flex items-center justify-between">

            <h1 class="text-2xl font-bold text-emerald-700">
                ☕ Coffee Shop
            </h1>

            <span class="text-sm text-stone-500">
                Fresh coffee & delicious food
            </span>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-6 py-12">

        <div class="mb-12 text-center">
            <h2 class="text-4xl font-bold mb-3">
                Our Menu
            </h2>

            <p class="text-stone-500">
                Choose your favorites from our menu.
            </p>
        </div>

        @foreach ($categories as $category)

            <section class="mb-14">
                <h3 class="text-2xl font-bold mb-6">
                    {{ $category->name }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2
                            lg:grid-cols-3 gap-6">

                    @foreach ($category->products as $product)

                        <div class="bg-white rounded-xl
                                    overflow-hidden shadow-sm">

                            <img
                                src="{{ $product->display_image_url }}"
                                alt="{{ $product->name }}"
                                class="w-full h-48 object-cover"
                            >

                            <div class="p-5">

                                <h4 class="text-xl font-semibold mb-2">
                                    {{ $product->name }}
                                </h4>

                                <p class="text-stone-500 text-sm mb-5">
                                    {{ $product->description }}
                                </p>

                                <div class="flex items-center
                                            justify-between">

                                    <span class="font-bold text-emerald-700">
                                        {{ $product->formatted_price }}
                                    </span>

                                    
                                    <form action="{{ route('cart.add', $product) }}" method="POST">
                                        @csrf

                                        <input type="hidden" name="quantity" value="1">

                                        <button
                                            type="submit"
                                            class="bg-emerald-600 hover:bg-emerald-700
                                                text-white px-4 py-2 rounded-lg
                                                cursor-pointer transition-colors">
                                            Add to cart
                                        </button>
                                    </form>


                                </div>
                            </div>
                        </div>

                    @endforeach

                </div>
            </section>

        @endforeach

    </main>

@if ($cartCount > 0)
    <section class="max-w-6xl mx-auto px-6 pb-16">

        <div class="bg-white rounded-2xl shadow-md p-6">

            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold">
                    🛒 Your Cart ({{ $cartCount }})
                </h2>
                <span class="font-bold text-emerald-700">
                    {{ number_format($cartTotal) }} VND
                </span>
            </div>

            <div class="space-y-4">
                @foreach ($cartItems as $item)
                    <div class="flex flex-wrap items-center
                                justify-between gap-4 border-b
                                border-stone-200 pb-4">

                        <div>
                            <h3 class="font-semibold">
                                {{ $item['product']->name }}
                            </h3>
                            <p class="text-sm text-stone-500">
                                {{ $item['product']->formatted_price }}
                            </p>
                        </div>

                        <div class="flex items-center gap-3">
                            <form
                                action="{{ route('cart.update', $item['product']) }}"
                                method="POST"
                                class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')

                                <input
                                    type="number"
                                    name="quantity"
                                    min="1"
                                    max="99"
                                    value="{{ $item['quantity'] }}"
                                    class="w-16 border border-stone-300
                                           rounded-lg p-2 text-center">

                                <button type="submit"
                                        class="text-emerald-700
                                               font-medium cursor-pointer">
                                    Update
                                </button>
                            </form>

                            <form
                                action="{{ route('cart.remove', $item['product']) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="text-red-600 font-medium
                                               cursor-pointer">
                                    Remove
                                </button>
                            </form>
                        </div>

                        <span class="font-bold">
                            {{ number_format($item['subtotal']) }} VND
                        </span>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex justify-end">
                <button type="button" disabled
                        class="bg-emerald-600 text-white
                               px-6 py-3 rounded-lg opacity-60">
                    Checkout (coming soon)
                </button>
            </div>

        </div>
    </section>
@endif

</body>
</html>


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

                                    <button
                                        type="button"
                                        disabled
                                        class="bg-emerald-600
                                               text-white px-4 py-2
                                               rounded-lg opacity-60
                                               cursor-not-allowed">
                                        Add to cart
                                    </button>

                                </div>
                            </div>
                        </div>

                    @endforeach

                </div>
            </section>

        @endforeach

    </main>

</body>
</html>

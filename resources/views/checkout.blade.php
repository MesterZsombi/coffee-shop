
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Coffee Shop</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-stone-50 text-stone-900">
    <main class="max-w-5xl mx-auto px-6 py-12">
        <a href="{{ route('coffee-shop.index') }}"
           class="text-emerald-700 hover:underline">
            ← Back to menu
        </a>

        <h1 class="text-4xl font-bold mt-6 mb-8">
            Checkout
        </h1>

        @if ($errors->any())
            <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="grid md:grid-cols-2 gap-8">

            <form action="{{ route('checkout.store') }}"
                  method="POST"
                  class="bg-white p-6 rounded-2xl shadow-sm space-y-5">
                @csrf

                <h2 class="text-2xl font-semibold">
                    Customer Details
                </h2>

                <div>
                    <label for="customer_name" class="block mb-2">
                        Full Name
                    </label>
                    <input id="customer_name" name="customer_name"
                           value="{{ old('customer_name') }}"
                           required maxlength="255"
                           class="w-full border border-stone-300 rounded-lg p-3">
                </div>

                <div>
                    <label for="email" class="block mb-2">
                        Email
                    </label>
                    <input id="email" type="email" name="email"
                           value="{{ old('email') }}"
                           required maxlength="255"
                           class="w-full border border-stone-300 rounded-lg p-3">
                </div>

                <div>
                    <label for="phone" class="block mb-2">
                        Phone
                    </label>
                    <input id="phone" name="phone"
                           value="{{ old('phone') }}"
                           required maxlength="30"
                           class="w-full border border-stone-300 rounded-lg p-3">
                </div>

                <div>
                    <label for="delivery_address" class="block mb-2">
                        Delivery Address
                    </label>
                    <textarea id="delivery_address"
                              name="delivery_address"
                              rows="4" required maxlength="1000"
                              class="w-full border border-stone-300 rounded-lg p-3">{{ old('delivery_address') }}</textarea>
                </div>

                <button type="submit"
                        class="w-full bg-emerald-700 hover:bg-emerald-800
                               text-white font-semibold py-3 rounded-xl
                               cursor-pointer">
                    Place Order
                </button>
            </form>

            <aside class="bg-white p-6 rounded-2xl shadow-sm self-start">
                <h2 class="text-2xl font-semibold mb-6">
                    Order Summary
                </h2>

                @foreach ($items as $item)
                    <div class="flex justify-between gap-4 py-3
                                border-b border-stone-200">
                        <span>
                            {{ $item['product']->name }}
                            × {{ $item['quantity'] }}
                        </span>
                        <span class="font-medium">
                            {{ number_format($item['subtotal']) }} VND
                        </span>
                    </div>
                @endforeach

                <div class="flex justify-between mt-6 text-xl font-bold">
                    <span>Total</span>
                    <span class="text-emerald-700">
                        {{ number_format($total) }} VND
                    </span>
                </div>
            </aside>
        </div>
    </main>
</body>
</html>

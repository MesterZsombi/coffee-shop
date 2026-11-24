
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed - Coffee Shop</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-stone-50 min-h-screen flex items-center justify-center">
    <div class="bg-white p-10 rounded-2xl shadow-sm text-center max-w-lg mx-4">
        <div class="text-6xl mb-5">☕</div>

        <h1 class="text-3xl font-bold text-emerald-700 mb-4">
            Order Placed Successfully!
        </h1>

        <p class="text-stone-600 mb-4">
            Thank you, {{ $order->customer_name }}!
        </p>

        <p class="mb-2">
            Order #{{ $order->id }}
        </p>

        <p class="text-xl font-semibold mb-8">
            Total: {{ number_format($order->total_amount) }} VND
        </p>

        <a href="{{ route('coffee-shop.index') }}"
           class="inline-block bg-emerald-700 text-white
                  px-6 py-3 rounded-xl hover:bg-emerald-800">
            Back to Coffee Shop
        </a>
    </div>
</body>
</html>

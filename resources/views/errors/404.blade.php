
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Coffee Shop</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-stone-50 flex items-center justify-center text-stone-900">

    <main class="text-center px-6">
        <div class="text-7xl mb-6">☕</div>

        <h1 class="text-7xl font-bold text-emerald-700 mb-4">
            404
        </h1>

        <h2 class="text-3xl font-semibold mb-4">
            Page Not Found
        </h2>

        <p class="text-stone-500 mb-8 max-w-md mx-auto">
            Looks like this page has gone out for coffee.
            Let's get you back to something delicious!
        </p>

        <a href="{{ route('coffee-shop.index') }}"
           class="inline-block bg-emerald-700 text-white
                  px-6 py-3 rounded-xl hover:bg-emerald-800
                  transition-colors">
            Back to Coffee Shop
        </a>
    </main>

</body>
</html>

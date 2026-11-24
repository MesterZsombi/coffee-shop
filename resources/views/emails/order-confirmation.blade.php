
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
</head>
<body>
    <h1>Thank you for your order!</h1>

    <p>Hello {{ $order->customer_name }},</p>

    <p>Your Coffee Shop order has been received.</p>

    <h2>Order #{{ $order->id }}</h2>

    <ul>
        @foreach ($order->items as $item)
            <li>
                {{ $item->product_name }}
                × {{ $item->quantity }}
                — {{ number_format($item->subtotal) }} VND
            </li>
        @endforeach
    </ul>

    <p>
        <strong>Total:</strong>
        {{ number_format($order->total_amount) }} VND
    </p>

    <p>Status: {{ ucfirst($order->status) }}</p>

    <p>Thank you for choosing Coffee Shop!</p>
</body>
</html>

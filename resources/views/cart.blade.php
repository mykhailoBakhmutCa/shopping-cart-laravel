<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping-cart-laravel</title>
</head>

<body>
    <div class="container">
        <div>
            @foreach ($cartItems as $cartItem)
                <p>product name:{{ $cartItem->product_name }}</p>
                <p>product price:{{ number_format($cartItem->price, 2) }}</p>
                <label for="quantity-{{ $cartItem->id }}">product quantity:</label>
                <input type="number" name="quantity-{{ $cartItem->id }}" id="quantity-{{ $cartItem->id }}"
                    value="{{ $cartItem->quantity }}" min=1 max=50 data-item-id={{ $cartItem->id }}
                    class="quantity-input">
                <p>product total price:{{ $cartItem->total_price }}</p>
                <br>
            @endforeach
        </div>
        <div>
            <p>Subtotal: {{ $totals['subtotal'] }}</p>
            <p>GST: {{ $totals['gst'] }} - {{ Config::get('taxes.gst') * 100 }}%</p>
            <p>QST: {{ $totals['qst'] }} - {{ Config::get('taxes.qst') * 100 }}%</p>
            <p>Total: {{ $totals['total'] }}</p>
        </div>
    </div>

    @vite('resources/js/app.js')
</body>

</html>

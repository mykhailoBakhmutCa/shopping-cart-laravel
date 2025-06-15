<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping-cart-laravel</title>
    <meta name='csrf-token' content="{{ csrf_token() }}">
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
                <p>product total price:{{ $cartItem->total_price_formatted }}</p>
                <br>
            @endforeach
        </div>
        <div>
            <p>Subtotal: <span id="subtotal">{{ $totals['subtotal'] }}</span></p>
            <p>GST: <span id="gst">{{ $totals['gst'] }}</span> - {{ Config::get('taxes.gst') * 100 }}%</p>
            <p>QST: <span id="qst">{{ $totals['qst'] }}</span> - {{ Config::get('taxes.qst') * 100 }}%</p>
            <p>Total: <span id="total">{{ $totals['total'] }}</span></p>
        </div>
    </div>

    @vite('resources/js/app.js')
</body>

</html>

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
        <h1>Your cart</h1>
        <div class="cart-item">
            @if ($cartItems->isEmpty())
                <p>Your cart is empty</p>
            @else
                @foreach ($cartItems as $cartItem)
                    <div class="cart-item" data-item-id="{{ $cartItem->id }}">
                        <div class="item-details">
                            <span class="item-name">{{ htmlspecialchars($cartItem->product_name) }}</spa>
                                <span class="item-price">${{ number_format($cartItem->price, 2, '.', '') }}</span>
                        </div>
                        <div class="item-quantity">
                            <label for="quantity-{{ $cartItem->id }}">Quantity:</label>
                            <input type="number" name="quantity-{{ $cartItem->id }}" id="quantity-{{ $cartItem->id }}"
                                value="{{ $cartItem->quantity }}" min="1" max="50"
                                data-product-id="{{ $cartItem->product_id }}" data-item-id="{{ $cartItem->id }}"
                                class="quantity-input">
                        </div>
                        <div class="item-total-price">
                            Summ: $<span class="item-line-total">{{ $cartItem->total_price_formatted }}</span>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <div class="cart-summary">
            <div class="summary-line">
                <span>Subtotal:</span>
                $<span id="subtotal">{{ $totals['subtotal'] }}</span>
            </div>
            <div class="summary-line">
                <span>GST ({{ Config::get('taxes.gst') * 100 }}%):</span>
                $<span id="gst">{{ $totals['gst'] }}</span>
            </div>
            <div class="summary-line">
                <span>QST ({{ Config::get('taxes.qst') * 100 }}%):</span>
                $<span id="qst">{{ $totals['qst'] }}</span>
            </div>
            <div class="summary-line total-line">
                <span>Total:</span>
                $<span id="total">{{ $totals['total'] }}</span>
            </div>
        </div>
    </div>

    @vite('resources/js/app.js')
</body>

</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping-cart-laravel</title>
</head>
<body>
    <h1>Init</h1>
    <ul>
        @foreach ($cartItems as $cartItem)
            <li>{{$cartItem->product_name}}</li>
        @endforeach
    </ul>
</body>
</html>
<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateQuantityRequest;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        $cartItems = $this->cartService->getSessionCartItems();
        $totals    = $this->cartService->calculateCartTotals($cartItems);

        return view('cart', compact('cartItems', 'totals'));
    }

    /**
     * @param UpdateQuantityRequest $request
     * @return JsonResponse
     */
    public function updateQuantity(UpdateQuantityRequest $request): JsonResponse
    {
        $itemId   = $request->input('item_id');
        $quantity = $request->input('quantity');

        $cartItem = $this->cartService->findCartItem($itemId);

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found in your cart',
            ], 404);
        }

        if (!$this->cartService->updateCartItemQuantity($cartItem, $quantity)) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cart item quantity.',
            ], 500);
        }

        $cartItems = $this->cartService->getSessionCartItems();
        $totals    = $this->cartService->calculateCartTotals($cartItems);

        return response()->json([
            'success' => true,
            'totals'  => $totals,
        ], 200);
    }
}

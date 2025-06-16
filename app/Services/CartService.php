<?php

namespace App\Services;

use App\Models\CartItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * @param int $itemId
     * @param string|null $sessionId
     * @return CartItem|null
     */
    public function findCartItem(int $itemId, ?string $sessionId = null): ?CartItem
    {
        $sessionId = $sessionId ?? Session::getId();
        return CartItem::where('id', $itemId)->where('session_id', $sessionId)->first();
    }

    /**
     * @param CartItem $cartItem
     * @param int $quantity
     * @return bool
     */
    public function updateCartItemQuantity(CartItem $cartItem, int $quantity): bool
    {
        $cartItem->quantity = $quantity;
        return $cartItem->save();
    }

    /**
     * @param string|null $sessionId
     * @return Collection<int,CartItem>
     */
    public function getSessionCartItems(?string $sessionId = null): Collection
    {
        $sessionId = $sessionId ?? Session::getId();
        $cartItems = CartItem::where('session_id', $sessionId)->get();

        if ($cartItems->isEmpty()) {
            $initialItems = CartItem::where('session_id', 'initial_session')->get();

            $newItems = $initialItems->map(function ($initialItem) use ($sessionId) {
                $newItem             = $initialItem->replicate();
                $newItem->session_id = $sessionId;
                return $newItem;
            });

            foreach ($newItems as $newItem) {
                $newItem->save();
            }

            return $newItems;
        }

        return $cartItems;
    }

    /**
     * @param Collection<int,CartItem> $cartItems
     * @return array<string, float>
     */
    public function calculateCartTotals(Collection $cartItems): array
    {
        $subTotal = $cartItems->sum(function ($item) {
            return $item->total_price;
        });

        $gst = $subTotal * Config::get('taxes.gst', 0);
        $qst = $subTotal * Config::get('taxes.qst', 0);

        $total = $subTotal + $gst + $qst;

        return [
            'subtotal' => round($subTotal, 2),
            'gst'      => round($gst, 2),
            'qst'      => round($qst, 2),
            'total'    => round($total, 2),
        ];
    }
}

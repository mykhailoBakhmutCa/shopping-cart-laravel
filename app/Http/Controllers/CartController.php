<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateQuantityRequest;
use App\Models\CartItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        $cartItems = $this->getSessionIdCartItems();
        $totals    = $this->calculeteCartTotals($cartItems);

        return view('cart', compact('cartItems', 'totals'));
    }

    /**
     * @param UpdateQuantityRequest $request
     * @return JsonResponse
     */
    public function updateQuantity(UpdateQuantityRequest $request): JsonResponse
    {
        $sessionId = Session::getId();
        $itemId    = $request->input('item_id');
        $quantity  = $request->input('quantity');

        $cartItem  = CartItem::where('id', $itemId)->where('session_id', $sessionId)->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found in your cart',
            ], 404);
        }

        $cartItem->quantity = $quantity;
        $cartItem->save();

        $cartItams = $this->getSessionIdCartItems();
        $totals    = $this->calculeteCartTotals($cartItams);

        return response()->json([
            'success' => true,
            'totals'  => $totals,
        ], 200);
    }

    /**
     * @return Collection
     */
    protected function getSessionIdCartItems(): Collection
    {
        $sessionId = Session::getId();
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
     * @return array
     */
    private function calculeteCartTotals(Collection $cartItems): array
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

<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

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
     * @return Collection
     */
    protected function getSessionIdCartItems(): Collection
    {
        $sessionId = Session::getId();
        $cartItems = CartItem::where('session_id', $sessionId)->get();

        if ($cartItems->isEmpty()) {
            $initialItems = CartItem::where('session_id', 'initial_session')->get();

            $newItems = $initialItems->map(function($initialItem) use ($sessionId) {
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
        $subTotal = $cartItems->sum(function($item) {
            return round($item->price * $item->quantity, 2);
        });

        $gst = round($subTotal * Config::get('taxes.gst', 0), 2);
        $qst = round($subTotal * Config::get('taxes.qst', 0), 2);

        $total = $subTotal + $gst + $qst;

        return [
            'subtotal' => $subTotal,
            'gst'      => $gst,
            'qst'      => $qst,
            'total'    => $total,
        ];
    }
}

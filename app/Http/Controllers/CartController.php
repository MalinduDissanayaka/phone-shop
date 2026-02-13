<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Phone;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Show Cart Page
    public function index()
    {
        $cartItems = Cart::with('phone')
            ->where('user_id', Auth::id())
            ->get();

        return view('cart', compact('cartItems'));
    }

    // Add to Cart
    public function add($phone_id)
    {
        $existing = Cart::where('user_id', Auth::id())
            ->where('phone_id', $phone_id)
            ->first();

        if ($existing) {
            $existing->increment('quantity');
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'phone_id' => $phone_id,
                'quantity' => 1,
            ]);
        }

        return redirect()->back()->with('success', 'Item added to cart!');
    }

    // Remove item
    public function remove($id)
    {
        Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        return redirect()->back()->with('success', 'Item removed!');
    }
}

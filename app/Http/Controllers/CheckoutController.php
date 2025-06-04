<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $carts = Cart::with('beverage.brand')
            ->where('user_id', auth()->id())
            ->get();

        if ($carts->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $carts->sum(function($cart) {
            return $cart->beverage->price * $cart->quantity;
        });

        $shipping = 15000;
        $total = $subtotal + $shipping;

        return view('user.checkout', compact('carts', 'subtotal', 'shipping', 'total'));
    }
}


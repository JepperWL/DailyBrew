<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Beverage;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::with('beverage.brand')
            ->where('user_id', auth()->id())
            ->get();
            
        $subtotal = $carts->sum(function($cart) {
            return $cart->beverage->price * $cart->quantity;
        });
        
        $total = $subtotal; 
        
        return view('user.cart', compact('carts', 'subtotal', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'beverage_id' => 'required|exists:beverages,id',
            'quantity' => 'required|integer|min:1|max:10',
            'note' => 'nullable|string|max:500'
        ]);

        $existingCart = Cart::where('user_id', auth()->id())
            ->where('beverage_id', $request->beverage_id)
            ->first();

        if ($existingCart) {
            
            $existingCart->update([
                'quantity' => $existingCart->quantity + $request->quantity,
                'note' => $request->note ?? $existingCart->note
            ]);
            $message = 'Item quantity updated in cart.';
        } else {
            
            Cart::create([
                'user_id' => auth()->id(),
                'beverage_id' => $request->beverage_id,
                'quantity' => $request->quantity,
                'note' => $request->note
            ]);
            $message = 'Item added to cart successfully.';
        }

        return redirect()->back()->with('success', $message);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
            'note' => 'nullable|string|max:500'
        ]);

        $cart = Cart::where('user_id', auth()->id())->findOrFail($id);
        
        $cart->update([
            'quantity' => $request->quantity,
            'note' => $request->note
        ]);

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }

    public function destroy($id)
    {
        $cart = Cart::where('user_id', auth()->id())->findOrFail($id);
        $cart->delete();
        
        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }

    public function updateNote(Request $request, $id)
    {
        $request->validate([
            'note' => 'nullable|string|max:500'
        ]);

        $cart = Cart::where('user_id', auth()->id())->findOrFail($id);
        
        $cart->update([
            'note' => $request->note
        ]);

        return redirect()->route('cart.index')->with('success', 'Note updated successfully.');
    }
}
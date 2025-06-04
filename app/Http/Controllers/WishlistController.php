<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Beverage;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::with('beverage.brand', 'beverage.category')
            ->where('user_id', auth()->id())
            ->get();
            
        return view('user.wishlist', compact('wishlists'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'beverage_id' => 'required|exists:beverages,id'
        ]);

        $existingWishlist = Wishlist::where('user_id', auth()->id())
            ->where('beverage_id', $request->beverage_id)
            ->first();

        if ($existingWishlist) {
            return redirect()->back()->with('error', 'Item already in your wishlist.');
        }

        Wishlist::create([
            'user_id' => auth()->id(),
            'beverage_id' => $request->beverage_id,
        ]);

        return redirect()->back()->with('success', 'Item added to wishlist.');
    }

    public function destroy($id)
    {
        $wishlist = Wishlist::where('user_id', auth()->id())->findOrFail($id);
        $wishlist->delete();
        
        return redirect()->route('wishlist.index')->with('success', 'Item removed from wishlist.');
    }
}
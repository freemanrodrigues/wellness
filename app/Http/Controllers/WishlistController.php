<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Add a product to the logged-in user's wishlist table.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $productId = $request->input('product_id');
        $userId = Auth::id();

        // Check if product is already in user's wishlist
        $existing = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            return redirect()->back()->with('info', 'This product is already saved in your wishlist. <a href="' . route('myaccount.wishlist') . '" class="fw-bold text-success text-decoration-underline ms-1">Manage Wishlist</a>');
        }

        // Insert record into `wishlists` table
        Wishlist::create([
            'user_id'    => $userId,
            'product_id' => $productId,
        ]);

        return redirect()->back()->with('success', 'Product added to your wishlist successfully! <a href="' . route('myaccount.wishlist') . '" class="fw-bold text-success text-decoration-underline ms-1">Manage Wishlist</a>');
    }

    /**
     * Remove a product from the user's wishlist table.
     */
    public function destroy($id)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('id', $id)
            ->delete();

        return redirect()->back()->with('success', 'Product removed from your wishlist.');
    }
}

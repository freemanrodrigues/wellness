<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class BasketController extends Controller
{
    /**
     * Store item into basket table.
     */
    public function store(Request $request): JsonResponse
    {
        Log::debug('Product store method was called.');
        $validated = $request->validate([
            'product_id'    => 'required|integer',
            'product_name'  => 'required|string|max:255',
            'product_image' => 'nullable|string|max:500',
            'sku'           => 'nullable|string|max:100',
            'price'         => 'required|numeric|min:0',
            'qty'           => 'required|integer|min:1|max:20',
        ]);

        $sessId = session()->getId();

        // Check if item is already in basket for this session using pid
        $existing = DB::table('baskets')
            ->where('sess_id', $sessId)
            ->where('pid', $validated['product_id'])
            ->where('basketflag', 'N')
            ->first();

        if ($existing) {
            DB::table('baskets')
                ->where('id', $existing->id)
                ->update([
                    'qty'        => $existing->qty + $validated['qty'],
                    'updated_at' => now(),
                ]);
        } else {
            $product = DB::table('products')->where('id', $validated['product_id'])->first();

            DB::table('baskets')->insert([
                'sess_id'               => $sessId,
                'pid'                   => $validated['product_id'],
                'cid'                   => $product->cid ?? null,
                'vid'                   => $product->vid ?? 0,
                'product_name'          => $validated['product_name'],
                'qty'                   => $validated['qty'],
                'prodprice'             => $validated['price'],
                'vendor_price'          => $product->vendorprice ?? null,
                'deliverycharge'        => $product->deliverycharge ?? null,
                'vendor_deliverycharge' => $product->vendordeliveryprice ?? null,
                'user_id'               => auth()->id(),
                'user_email'            => auth()->check() ? auth()->user()->email : null,
                'basketflag'            => 'N', // N = active/in-cart, Y = converted to order
                'created_at'            => now(),
                'updated_at'            => now(),
            ]);
            Log::debug('Product stored into baskets DB');
        }

        $cartCount = DB::table('baskets')
            ->where('sess_id', $sessId)
            ->where('basketflag', 'N')
            ->sum('qty');

        Log::debug('Product store cart_count: ' . $cartCount);

        return response()->json([
            'success'    => true,
            'message'    => 'Item added to your cart.',
            'cart_count' => $cartCount,
        ]);
    }

    /**
     * Display the cart page.
     */
    public function index(): View
    {
        $sessId = session()->getId();

        $items = DB::table('baskets')
            ->where('sess_id', $sessId)
            ->where('basketflag', 'N')
            ->orderByDesc('created_at')
            ->get();

        $subtotal = $items->sum(fn($item) => $item->prodprice * $item->qty);

        $discount = 0;
        $promoCode = Session::get('promo_code');

        if ($promoCode) {
            $discount = Session::get('promo_discount', 0);
        }

        $total = max(0, $subtotal - $discount);

        $availableCoupons = [
            ['code' => 'WELCOME10', 'description' => '10% off your first order'],
            ['code' => 'FREESHIP', 'description' => 'Free delivery on orders over $50'],
        ];

        $meta = [
            'title' => 'Your Cart',
            'description' => 'Review the items in your shopping cart.',
        ];

        return view('cart.cart', compact('items', 'subtotal', 'discount', 'total', 'promoCode', 'availableCoupons', 'meta'));
    }

    /**
     * Update quantity for a basket line (AJAX).
     */
    public function updateQty(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'qty' => 'required|integer|min:1|max:20',
        ]);

        $sessId = session()->getId();

        $item = DB::table('baskets')
            ->where('id', $id)
            ->where('sess_id', $sessId)
            ->first();

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item not found.'], 404);
        }

        DB::table('baskets')->where('id', $id)->update([
            'qty'        => $validated['qty'],
            'updated_at' => now(),
        ]);

        $subtotal = DB::table('baskets')
            ->where('sess_id', $sessId)
            ->where('basketflag', 'N')
            ->get()
            ->sum(fn($row) => $row->prodprice * $row->qty);

        return response()->json([
            'success'       => true,
            'line_subtotal' => number_format($item->prodprice * $validated['qty'], 2),
            'cart_subtotal' => number_format($subtotal, 2),
        ]);
    }

    /**
     * Remove an item from the basket (AJAX).
     */
    public function destroy(int $id): JsonResponse
    {
        $sessId = session()->getId();

        $deleted = DB::table('baskets')
            ->where('id', $id)
            ->where('sess_id', $sessId)
            ->delete();

        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Item not found.'], 404);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Apply a promo code (AJAX).
     */
    public function applyPromo(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'promo_code' => 'required|string|max:50',
        ]);

        $code = strtoupper(trim($validated['promo_code']));

        $validCodes = [
            'WELCOME10' => 0.10, // 10% off
            'FREESHIP'   => 0,    // handled separately
        ];

        if (!array_key_exists($code, $validCodes)) {
            return response()->json(['success' => false, 'message' => 'That promo code is not valid.'], 422);
        }

        $sessId = session()->getId();
        $subtotal = DB::table('baskets')
            ->where('sess_id', $sessId)
            ->where('basketflag', 'N')
            ->get()
            ->sum(fn($row) => $row->prodprice * $row->qty);

        $discount = round($subtotal * $validCodes[$code], 2);

        Session::put('promo_code', $code);
        Session::put('promo_discount', $discount);

        return response()->json([
            'success'  => true,
            'message'  => "Promo code {$code} applied.",
            'discount' => number_format($discount, 2),
            'total'    => number_format(max(0, $subtotal - $discount), 2),
        ]);
    }

    /**
     * Remove the currently applied promo code.
     */
    public function removePromo(): JsonResponse
    {
        Session::forget(['promo_code', 'promo_discount']);

        return response()->json(['success' => true]);
    }
}

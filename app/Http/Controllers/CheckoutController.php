<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Display the Shipping Details page (views/cart/shipping.blade.php).
     */
    public function shipping(): View|RedirectResponse
    {
        $sessId = session()->getId();

        $items = DB::table('baskets')
            ->where('sess_id', $sessId)
            ->where('basketflag', 'N')
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty. Please add items before proceeding to checkout.');
        }

        $subtotal = $items->sum(fn($item) => $item->prodprice * $item->qty);
        $discount = Session::get('promo_discount', 0);
        $total = max(0, $subtotal - $discount);

        // Fetch previous distinct delivery addresses for the logged-in user
        $previousAddresses = DB::table('shipping_addresses')
            ->where('user_id', Auth::id())
            ->whereNotNull('s_address1')
            ->whereNotNull('s_pincode')
            ->select([
                's_firstname',
                's_lastname',
                's_phone',
                's_pincode',
                's_address1',
                's_address2',
                's_landmark',
                's_city',
                's_state',
                's_country_id',
                's_email'
            ])
            /*   ->groupBy([
                   's_firstname',
                   's_lastname',
                   's_phone',
                   's_pincode',
                   's_address1',
                   's_address2',
                   's_landmark',
                   's_city',
                   's_state',
                   's_country_id',
                   's_email'
               ]) */
            ->latest('id')
            ->take(5)
            ->get();

        $previousAddresses = array();
        // Fetch countries from countries table
        $countries = DB::table('countries')
            ->where('active', 1)
            ->orderBy('countryname')
            ->pluck('countryname', 'id');

        if ($countries->isEmpty()) {
            $countries = collect([
                1 => 'India',
                2 => 'United States',
                3 => 'United Kingdom',
                4 => 'Canada',
                5 => 'Australia',
                6 => 'United Arab Emirates',
            ]);
        }


        $meta = [
            'title' => 'Shipping Details',
            'description' => 'Provide your delivery address to complete your order.',
        ];

        return view('cart.shipping', compact(
            'items',
            'subtotal',
            'discount',
            'total',
            'previousAddresses',
            'countries',
            'meta'
        ));
    }

    /**
     * Save shipping details to the user's active basket items and proceed to payment.
     */
    public function storeShipping(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            's_firstname' => 'required|string|max:255',
            's_lastname' => 'required|string|max:255',
            's_phone' => 'required|string|max:20',
            's_pincode' => 'required|string|max:20',
            's_address1' => 'required|string|max:500',
            's_address2' => 'nullable|string|max:500',
            's_landmark' => 'nullable|string|max:255',
            's_city' => 'required|string|max:255',
            's_state' => 'required|string|max:255',
            's_country_id' => 'required|integer',
            's_email' => 'nullable|email|max:255',
        ]);

        $sessId = session()->getId();
        $user = Auth::user();

        // Attach shipping details & user ID to active basket items
        DB::table('baskets')
            ->where('sess_id', $sessId)
            ->where('basketflag', 'N')
            ->update([
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_ip' => $request->ip(),
                's_firstname' => $validated['s_firstname'],
                's_lastname' => $validated['s_lastname'],
                's_phone' => $validated['s_phone'],
                's_pincode' => $validated['s_pincode'],
                's_address1' => $validated['s_address1'],
                's_address2' => $validated['s_address2'] ?? null,
                's_landmark' => $validated['s_landmark'] ?? null,
                's_city' => $validated['s_city'],
                's_state' => $validated['s_state'],
                's_country_id' => $validated['s_country_id'],
                's_email' => $validated['s_email'] ?? $user->email,
                'updated_at' => now(),
            ]);

        return redirect()->route('checkout.payment');
    }

    /**
     * Display the Payment page (views/cart/payment.blade.php).
     */
    public function payment(): View|RedirectResponse
    {
        $sessId = session()->getId();

        $items = DB::table('baskets')
            ->where('sess_id', $sessId)
            ->where('basketflag', 'N')
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $items->sum(fn($item) => $item->prodprice * $item->qty);
        $discount = Session::get('promo_discount', 0);
        $total = max(0, $subtotal - $discount);

        $meta = [
            'title' => 'Payment Options',
            'description' => 'Select payment method to complete your purchase.',
        ];

        return view('cart.payment', compact('items', 'subtotal', 'discount', 'total', 'meta'));
    }

    /**
     * Process payment submission and confirm order.
     */
    public function storePayment(Request $request): RedirectResponse
    {
        $sessId = session()->getId();

        // Convert active cart items to placed order (basketflag = 'Y')
        DB::table('baskets')
            ->where('sess_id', $sessId)
            ->where('basketflag', 'N')
            ->update([
                'basketflag' => 'Y',
                'updated_at' => now(),
            ]);

        Session::forget(['promo_code', 'promo_discount']);

        return redirect()->route('checkout.confirm');
    }

    /**
     * Display Order Confirmation page (views/cart/confirm.blade.php).
     */
    public function confirm(): View
    {
        $orderNumber = rand(100000, 999999);

        $meta = [
            'title' => 'Order Confirmed',
            'description' => 'Thank you for your order.',
        ];

        return view('cart.confirm', compact('orderNumber', 'meta'));
    }
}

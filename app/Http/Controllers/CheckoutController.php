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
     * Process payment submission and create order:
     * 1. Calculate price totals and create Order Invoice record in `order_invoice`.
     * 2. Copy cart items from `baskets` into `fin_basket` with assigned `order_id`.
     * 3. Update `baskets` setting `basketflag = 'Y'`.
     * 4. Forward order details to payment gateway / confirmation.
     */
    public function storePayment(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'payment_method' => 'nullable|string|max:50',
        ]);

        $paymentMethod = $validated['payment_method'] ?? 'paypal';
        $sessId = session()->getId();

        // 1. Fetch active items from `baskets` table
        $items = DB::table('baskets')
            ->where('sess_id', $sessId)
            ->where('basketflag', 'N')
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty or order has already been processed.');
        }

        // 2. Generate unique Order ID / Order Number
        $orderId = 'ORD-' . date('Ymd') . '-' . rand(10000, 99999);

        // 3. Price calculations
        $subtotal = $items->sum(fn($item) => $item->prodprice * $item->qty);
        $discount = (float) Session::get('promo_discount', 0);
        $deliverycharge = (float) $items->sum('deliverycharge');
        $totalamount = max(0, $subtotal - $discount + $deliverycharge);

        // 4. Store Order Invoice details in `order_invoice` table
        $invoiceId = DB::table('order_invoice')->insertGetId([
            'user_id'              => Auth::id(),
            'totalamount'          => $totalamount,
            'orderdiscount'        => $subtotal,
            'promo_discount'       => $discount,
            'deliverycharge'       => $deliverycharge,
            'gateway_id'           => $orderId,
            'orderstatus'          => ($paymentMethod === 'paypal' ? 'Pending PayPal Payment' : 'Pending'),
            'sess_id'              => $sessId,
            'shopflag'             => 'Online',
            'affiliate_id'         => null,
            'affiliate_commission' => 0.00,
            'error_code'           => null,
            'error_message'        => null,
            'cardname'             => $paymentMethod,
            'cardnumber'           => null,
            'created_at'           => now(),
            'updated_at'           => now(),
        ]);

        // 5. Copy products from `baskets` table to `fin_basket` table
        foreach ($items as $item) {
            DB::table('fin_basket')->insert([
                'order_id'              => $orderId,
                'pid'                   => $item->pid,
                'cid'                   => $item->cid ?? null,
                'vid'                   => $item->vid ?? null,
                'product_name'          => $item->product_name,
                'qty'                   => $item->qty,
                'product_price'         => $item->prodprice,
                'vendor_price'          => $item->vendor_price ?? 0,
                'pdiscount'             => $item->pdiscount ?? 0,
                'deliverycharge'        => $item->deliverycharge ?? 0,
                'vendor_deliverycharge' => $item->vendor_deliverycharge ?? 0,
                'sess_id'               => $item->sess_id,
                'user_email'            => $item->user_email,
                'user_id'               => $item->user_id ?? Auth::id(),
                'user_ip'               => $item->user_ip ?? $request->ip(),
                's_firstname'           => $item->s_firstname,
                's_lastname'            => $item->s_lastname,
                's_address1'            => $item->s_address1,
                's_address2'            => $item->s_address2,
                's_landmark'            => $item->s_landmark,
                's_city'                => $item->s_city,
                's_state'               => $item->s_state,
                's_pincode'             => $item->s_pincode,
                's_country_id'          => $item->s_country_id,
                's_email'               => $item->s_email,
                's_phone'               => $item->s_phone,
                'cardmessage'           => $item->cardmessage ?? null,
                'deliverydate'          => $item->deliverydate ?? null,
                'basketflag'            => 'Y',
                'created_at'            => now(),
                'updated_at'            => now(),
            ]);
        }

        // 6. Update `baskets` table: set `basketflag = 'Y'`
        DB::table('baskets')
            ->where('sess_id', $sessId)
            ->where('basketflag', 'N')
            ->update([
                'basketflag' => 'Y',
                'updated_at' => now(),
            ]);

        // 7. Save order IDs to session and clear promo discounts
        Session::put('placed_order_id', $orderId);
        Session::put('placed_invoice_id', $invoiceId);
        Session::forget(['promo_code', 'promo_discount']);

        return redirect()->route('checkout.confirm');
    }

    /**
     * Display Order Confirmation page (views/cart/confirm.blade.php).
     */
    public function confirm(): View
    {
        $orderId = Session::get('placed_order_id');

        $invoice = null;
        $finItems = collect();

        if ($orderId) {
            $invoice = DB::table('order_invoice')->where('gateway_id', $orderId)->first();
            $finItems = DB::table('fin_basket')->where('order_id', $orderId)->get();
        } else {
            // Fallback for logged-in user if session refreshed
            $invoice = DB::table('order_invoice')->where('user_id', Auth::id())->latest('id')->first();
            if ($invoice) {
                $orderId = $invoice->gateway_id;
                $finItems = DB::table('fin_basket')->where('order_id', $orderId)->get();
            }
        }

        $meta = [
            'title' => 'Order Confirmed - ' . ($orderId ?? 'Wellness'),
            'description' => 'Thank you for your order.',
        ];

        return view('cart.confirm', compact('orderId', 'invoice', 'finItems', 'meta'));
    }
}

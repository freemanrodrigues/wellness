<?php

namespace App\Http\Controllers;

use App\Models\{Category, OrderInvoice, FinBasket, Product, Wishlist};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MyAccountController extends Controller
{
    /**
     * Helper to retrieve categories for navbar layout context.
     */
    private function getNavData(): array
    {
        $nav = Category::getTopCategoriesWithSubcategories();
        return [
            'category'    => $nav['categories'] ?? [],
            'subcategory' => $nav['subcategories'] ?? [],
        ];
    }

    /**
     * My Account Dashboard (/myaccount/home)
     */
    public function home(Request $request): View
    {
        $user = Auth::user();
        $nav = $this->getNavData();

        $meta = [
            'title'       => 'My Account Dashboard',
            'description' => 'Manage your profile, orders, coupons, wishlist, addresses, and preferences.',
        ];

        $ordersCount = OrderInvoice::where('user_id', Auth::id())->count();
        $wishlistCount = Wishlist::where('user_id', Auth::id())->count();

        return view('myaccount.index', array_merge($nav, [
            'user' => $user,
            'meta' => $meta,
            'stats' => [
                'orders_count'    => $ordersCount,
                'coupons_count'   => 4,
                'wishlist_count'  => $wishlistCount,
                'addresses_count' => 2,
                'gift_balance'    => 150.00,
            ]
        ]));
    }

    /**
     * Profile Management (/myaccount/profile)
     */
    public function profile(Request $request): View
    {
        $user = Auth::user();
        $nav = $this->getNavData();

        $meta = [
            'title'       => 'My Profile - Account Details',
            'description' => 'View and update your profile information and password.',
        ];

        return view('myaccount.profile', array_merge($nav, [
            'user' => $user,
            'meta' => $meta,
        ]));
    }

    /**
     * Orders History (/myaccount/orders)
     * Pulls order invoices from `order_invoice` table for the logged-in user,
     * and product details from `fin_basket` and `products` tables.
     */
    public function orders(Request $request): View
    {
        $user = Auth::user();
        $nav = $this->getNavData();

        $meta = [
            'title'       => 'My Orders',
            'description' => 'View your order history, track shipments, and download invoices.',
        ];

        // Fetch placed order invoices for the authenticated user along with fin_basket items and product details
        $invoices = OrderInvoice::where('user_id', Auth::id())
            ->with(['items.product'])
            ->latest('id')
            ->get();

        $orders = $invoices->map(function ($inv) {
            $statusClass = match (strtolower($inv->orderstatus ?? '')) {
                'delivered', 'completed' => 'bg-success',
                'in transit', 'dispatched' => 'bg-primary',
                'cancelled', 'failed' => 'bg-danger',
                default => 'bg-warning text-dark',
            };

            $items = $inv->items->map(function ($item) {
                $img = $item->product->imgurl ?? null;
                if ($img && !str_starts_with($img, 'http') && !str_starts_with($img, 'images/')) {
                    $img = 'images/products/' . $img;
                }

                return [
                    'id'           => $item->pid,
                    'name'         => $item->product_name ?: ($item->product->name ?? 'Product Item'),
                    'qty'          => $item->qty,
                    'price'        => $item->product_price,
                    'img'          => $img ?: 'images/products/default.jpg',
                    'metaurl'      => $item->product->metaurl ?? null,
                    'product_code' => $item->product->sku ?? null,
                ];
            })->toArray();

            return [
                'id'             => $inv->gateway_id ?: ('ORD-' . $inv->id),
                'date'           => $inv->created_at ? $inv->created_at->format('M d, Y') : 'N/A',
                'status'         => $inv->orderstatus ?? 'Pending',
                'status_class'   => $statusClass,
                'total'          => $inv->totalamount,
                'items_count'    => $inv->items->sum('qty'),
                'payment_method' => $inv->cardname ?: 'Online Payment',
                'items'          => $items,
            ];
        })->toArray();

        return view('myaccount.orders', array_merge($nav, [
            'user'   => $user,
            'meta'   => $meta,
            'orders' => $orders,
        ]));
    }

    /**
     * Discount Coupons (/myaccount/coupons)
     */
    public function coupons(Request $request): View
    {
        $user = Auth::user();
        $nav = $this->getNavData();

        $meta = [
            'title'       => 'My Discount Coupons',
            'description' => 'View active coupons, promo codes, and discount vouchers.',
        ];

        $coupons = [
            [
                'code' => 'WELCOME20',
                'title' => '20% OFF Site-Wide',
                'description' => 'Get 20% off on your first order. No minimum purchase required.',
                'expiry' => 'Oct 31, 2026',
                'discount' => '20% OFF',
                'badge' => 'Exclusive',
                'badge_class' => 'bg-success',
                'min_spend' => '$0.00',
            ],
            [
                'code' => 'HEALTH15',
                'title' => '$15 Flat Discount',
                'description' => 'Applicable on all Wellness & Herbal supplements above $75.',
                'expiry' => 'Sep 30, 2026',
                'discount' => '$15 OFF',
                'badge' => 'Popular',
                'badge_class' => 'bg-primary',
                'min_spend' => '$75.00',
            ],
            [
                'code' => 'FREESHIP',
                'title' => 'Free Express Shipping',
                'description' => 'Free priority standard delivery on order items above $50.',
                'expiry' => 'Dec 31, 2026',
                'discount' => 'FREE SHIP',
                'badge' => 'Free Shipping',
                'badge_class' => 'bg-info text-dark',
                'min_spend' => '$50.00',
            ],
            [
                'code' => 'FESTIVE30',
                'title' => '30% Holiday Special',
                'description' => 'Special seasonal voucher for festive care packs.',
                'expiry' => 'Nov 15, 2026',
                'discount' => '30% OFF',
                'badge' => 'Upcoming',
                'badge_class' => 'bg-warning text-dark',
                'min_spend' => '$100.00',
            ],
        ];

        return view('myaccount.coupons', array_merge($nav, [
            'user'    => $user,
            'meta'    => $meta,
            'coupons' => $coupons,
        ]));
    }

    /**
     * Wishlist (/myaccount/wishlist)
     */
    public function wishlist(Request $request): View
    {
        $user = Auth::user();
        $nav = $this->getNavData();

        $meta = [
            'title'       => 'My Wishlist',
            'description' => 'View and manage your saved products and favorite items.',
        ];

        $userWishlists = Wishlist::where('user_id', Auth::id())
            ->with('product')
            ->latest('id')
            ->get();

        $wishlistItems = $userWishlists->map(function ($w) {
            $p = $w->product;
            $img = $p->imgurl ?? null;
            if ($img && !str_starts_with($img, 'http') && !str_starts_with($img, 'images/')) {
                $img = 'images/products/' . $img;
            }

            return [
                'wishlist_id'    => $w->id,
                'id'             => $p->id ?? $w->product_id,
                'name'           => $p->name ?? ('Product #' . $w->product_id),
                'category'       => 'Wellness Product',
                'price'          => $p->price ?? 0.00,
                'original_price' => $p ? ($p->price * 1.15) : null,
                'image'          => $img ?: 'images/products/default.jpg',
                'in_stock'       => $p ? ($p->isactive == 1) : true,
                'rating'         => 4.8,
            ];
        })->toArray();

        return view('myaccount.wishlist', array_merge($nav, [
            'user'          => $user,
            'meta'          => $meta,
            'wishlistItems' => $wishlistItems,
        ]));
    }

    /**
     * Address Book (/myaccount/addresses)
     */
    public function addresses(Request $request): View
    {
        $user = Auth::user();
        $nav = $this->getNavData();

        $meta = [
            'title'       => 'Address Book',
            'description' => 'Manage your shipping and billing addresses.',
        ];

        $addresses = [
            [
                'id' => 1,
                'title' => 'Home (Default Shipping)',
                'is_default_shipping' => true,
                'is_default_billing' => true,
                'name' => trim(($user->firstname ?? 'John') . ' ' . ($user->lastname ?? 'Doe')),
                'phone' => $user->phone ?? '+1 (555) 019-2834',
                'address1' => $user->address1 ?? '742 Evergreen Terrace',
                'address2' => $user->address ?? 'Suite 4B',
                'landmark' => $user->landmark ?? 'Near City Park',
                'city' => $user->city ?? 'Springfield',
                'state' => $user->state ?? 'OR',
                'pincode' => $user->pincode ?? '97477',
                'country' => 'United States',
            ],
            [
                'id' => 2,
                'title' => 'Office / Workplace',
                'is_default_shipping' => false,
                'is_default_billing' => false,
                'name' => trim(($user->firstname ?? 'John') . ' ' . ($user->lastname ?? 'Doe')),
                'phone' => $user->phone ?? '+1 (555) 019-2834',
                'address1' => '100 Tech Plaza, 5th Floor',
                'address2' => 'Building B',
                'landmark' => 'Financial District',
                'city' => $user->city ?? 'Springfield',
                'state' => $user->state ?? 'OR',
                'pincode' => $user->pincode ?? '97478',
                'country' => 'United States',
            ],
        ];

        return view('myaccount.addresses', array_merge($nav, [
            'user'      => $user,
            'meta'      => $meta,
            'addresses' => $addresses,
        ]));
    }

    /**
     * Communication Preferences (/myaccount/preferences)
     */
    public function preferences(Request $request): View
    {
        $user = Auth::user();
        $nav = $this->getNavData();

        $meta = [
            'title'       => 'Communication Preferences',
            'description' => 'Configure your email, SMS, and notification preferences.',
        ];

        $preferences = [
            'order_updates_email' => true,
            'order_updates_sms'   => true,
            'promotional_emails'  => true,
            'newsletter'          => false,
            'back_in_stock'       => true,
            'price_drop_alerts'   => true,
            'whatsapp_alerts'     => false,
        ];

        return view('myaccount.preferences', array_merge($nav, [
            'user'        => $user,
            'meta'        => $meta,
            'preferences' => $preferences,
        ]));
    }

    /**
     * Gift Cards (/myaccount/gift-cards)
     */
    public function giftCards(Request $request): View
    {
        $user = Auth::user();
        $nav = $this->getNavData();

        $meta = [
            'title'       => 'Gift Cards & Wallet',
            'description' => 'Check gift card balance, redeem vouchers, and purchase digital gift cards.',
        ];

        $giftCardData = [
            'balance' => 150.00,
            'active_cards' => [
                [
                    'card_number' => '•••• •••• •••• 9821',
                    'balance' => 100.00,
                    'expiry' => 'Dec 31, 2027',
                    'status' => 'Active',
                ],
                [
                    'card_number' => '•••• •••• •••• 4402',
                    'balance' => 50.00,
                    'expiry' => 'Jun 30, 2027',
                    'status' => 'Active',
                ],
            ],
            'transactions' => [
                [
                    'date' => 'Aug 18, 2026',
                    'description' => 'Gift Card Redeemed (#GC-9821)',
                    'amount' => '+$100.00',
                    'type' => 'credit',
                ],
                [
                    'date' => 'Jul 15, 2026',
                    'description' => 'Applied on Order #ORD-2026-6102',
                    'amount' => '-$50.00',
                    'type' => 'debit',
                ],
                [
                    'date' => 'Jul 01, 2026',
                    'description' => 'Gift Card Redeemed (#GC-4402)',
                    'amount' => '+$100.00',
                    'type' => 'credit',
                ],
            ]
        ];

        return view('myaccount.gift-cards', array_merge($nav, [
            'user'         => $user,
            'meta'         => $meta,
            'giftCardData' => $giftCardData,
        ]));
    }
}

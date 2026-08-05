<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class MainController extends Controller
{


    public function home(): View
    {
        $meta = [
            'title' => 'Flower & Gift Delivery Online',
            'description' => 'Order fresh flowers, cakes, and gifts online with same-day delivery.',
        ];

        // Replace with real queries once your Product model/relationships are ready, e.g.:
        // 'products' => Product::where('category', 'best-sellers')->take(4)->get()
        $productRows = [
            [
                'title' => 'Nutrition & Diet',
                'view_all' => '#',
                'products' => [
                    ['name' => 'Whey Protein Isolate - Vanilla', 'price' => 54.99, 'image' => '/images/products/whey-protein-vanilla.jpg'],
                    ['name' => 'Omega-3 Fish Oil Capsules', 'price' => 28.50, 'image' => '/images/products/omega-3-capsules.jpg'],
                    ['name' => 'Organic Matcha Green Tea Powder', 'price' => 24.99, 'image' => '/images/products/organic-matcha.jpg'],
                    ['name' => 'Plant-Based Meal Replacement Shake', 'price' => 39.99, 'image' => '/images/products/plant-meal-shake.webp'],
                ],
            ],
            [
                'title' => 'Fitness & Movement',
                'view_all' => '#',
                'products' => [
                    ['name' => 'Non-Slip Eco Yoga Mat', 'price' => 35.00, 'image' => '/images/products/eco-yoga-mat.webp'],
                    ['name' => 'Resistance Band Set (5 Levels)', 'price' => 19.99, 'image' => '/images/products/resistance-bands.jpg'],
                    ['name' => 'Adjustable Dumbbells Set', 'price' => 110.50, 'image' => '/images/products/adjustable-dumbbells.webp'],
                    ['name' => 'High-Density Deep Tissue Foam Roller', 'price' => 22.99, 'image' => '/images/products/foam-roller.webp'],
                ],
            ],
            [
                'title' => 'Mental Wellbeing',
                'view_all' => '#',
                'products' => [
                    ['name' => 'Authentic Himalayan Salt Lamp', 'price' => 29.99, 'image' => '/images/products/himalayan-salt-lamp.jpg'],
                    ['name' => 'Stress Relief Calming Herbal Tea', 'price' => 14.50, 'image' => '/images/products/calming-herbal-tea.jpg'],
                    ['name' => 'Acupressure Mat and Pillow Set', 'price' => 38.00, 'image' => '/images/products/acupressure-mat.jpg'],
                    ['name' => 'Natural Sleep Aid Gummies', 'price' => 18.99, 'image' => '/images/products/sleep-aid-gummies.jpg'],
                ],
            ],
            [
                'title' => 'Ayurveda',
                'view_all' => '#',
                'products' => [
                    ['name' => 'Ashwagandha Root Extract', 'price' => 24.99, 'image' => '/images/products/ashwagandha-extract.webp'],
                    ['name' => 'Triphala Digestive Churna', 'price' => 18.50, 'image' => '/images/products/triphala-churna.jpg'],
                    ['name' => 'Brahmi Brain Wellness Drops', 'price' => 22.00, 'image' => '/images/products/brahmi-drops.jpg'],
                    ['name' => 'Kumkumadi Face Glow Oil', 'price' => 45.99, 'image' => '/images/products/kumkumadi-oil.jpg'],
                ],
            ],
        ];

        return view('home', ['meta' => $meta, 'productRows' => $productRows]);
    }

    /**
     * Display the About Us page.
     */
    public function about(): View
    {
        $meta = [
            'title' => 'About Us',
            'description' => 'Learn more about ' . config('app.name') . ' and what we do.',
        ];

        return view('about', ['meta' => $meta]);
    }

    /**
     * Display the Privacy Policy page.
     */
    public function privacy(): View
    {
        $meta = [
            'title' => 'Privacy Policy',
            'description' => 'Learn how ' . config('app.name') . ' collects, uses, and protects your personal information.',
        ];

        return view('privacy', ['meta' => $meta]);
    }

    /**
     * Display the Terms of Service page.
     */
    public function terms(): View
    {
        $meta = [
            'title' => 'Terms of Service',
            'description' => 'Read the Terms of Service governing your use of ' . config('app.name') . '.',
        ];

        return view('terms', ['meta' => $meta]);
    }

    public function contact(): View
    {
        $meta = [
            'title' => 'Contact Us',
            'description' => 'Read the Terms of Service governing your use of ' . config('app.name') . '.',
        ];

        return view('contact', ['meta' => $meta]);
    }
}
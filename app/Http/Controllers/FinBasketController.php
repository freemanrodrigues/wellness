<?php

namespace App\Http\Controllers;

use App\Models\FinBasket;
use Illuminate\Http\Request;

class FinBasketController extends Controller
{
    /**
     * Display a listing of finalized baskets.
     */
    public function index()
    {
        $finBaskets = FinBasket::latest()->paginate(20);
        return response()->json($finBaskets);
    }
}

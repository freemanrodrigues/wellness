<?php

namespace App\Http\Controllers;

use App\Models\OrderInvoice;
use Illuminate\Http\Request;

class OrderInvoiceController extends Controller
{
    /**
     * Display a listing of order invoices.
     */
    public function index()
    {
        $invoices = OrderInvoice::with('items')->latest()->paginate(20);
        return response()->json($invoices);
    }
}

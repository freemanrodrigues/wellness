<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function getCountries()
    {
        return $countries = Country::where('is_active', 1)->get(['id', 'name']);
        //return response()->json($countries);
    }
}

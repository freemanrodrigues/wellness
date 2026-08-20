<?php

namespace App\Http\Controllers\Auth;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {

        $nav = Category::getTopCategoriesWithSubcategories();

        $meta = [
            'title' => 'About Us',
            'description' => 'Learn more about ' . config('app.name') . ' and what we do.',
        ];
        return view('auth.login', ['meta' => $meta, 'category' => $nav['categories'], 'subcategory' => $nav['subcategories']]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {

        $request->authenticate();

        $request->session()->regenerate();

        if (Auth::user()->user_role == 'C')
            return redirect()->intended(route('home', absolute: false));
        else
            return redirect()->intended(route('dashboard.index', absolute: false));

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BasketController;
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
    public function create(Request $request): View
    {
        if (!session()->has('url.intended')) {
            $prevUrl = url()->previous();
            if ($prevUrl && $prevUrl !== $request->url()) {
                $path = parse_url($prevUrl, PHP_URL_PATH);
                if ($path !== '/login' && $path !== '/register') {
                    session()->put('url.intended', $prevUrl);
                }
            }
        }

        $nav = Category::getTopCategoriesWithSubcategories();

        $meta = [
            'title' => 'Log In',
            'description' => 'Log in to your account on ' . config('app.name') . '.',
        ];
        return view('auth.login', ['meta' => $meta, 'category' => $nav['categories'], 'subcategory' => $nav['subcategories']]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Capture guest session ID before login regeneration
        $oldSessId = session()->getId();

        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        $newSessId = session()->getId();

        // Merge guest basket items to authenticated user & new session ID
        BasketController::mergeGuestBasket($oldSessId, $newSessId, $user);

        if ($user->user_role == 'C')
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

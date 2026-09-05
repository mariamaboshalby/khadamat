<?php

namespace App\Http\Controllers\Auth;

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
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Redirect based on role, ignoring any stored API/JSON intended URLs
        $intended = session()->pull('url.intended');

        // Ignore API endpoints as intended redirect targets
        if ($intended && !str_contains($intended, 'notifications/unread-count') && !str_contains($intended, 'api/')) {
            return redirect($intended);
        }

        if ($user->hasRole('admin') || $user->user_type === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('technician') || $user->user_type === 'technician') {
            return redirect()->route('technician.repair-requests.index');
        }

        return redirect()->route('dashboard');
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

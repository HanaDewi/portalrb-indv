<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
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
        $user = User::where('username', $request->username)->first();
        if ($user && in_array($user->level, ['tpn', 'tpm', 'kabupaten', 'provinsi', 'kl', 'admin'])) {
            $request->authenticate();
            $request->session()->regenerate();
            if ($request->modul == 'evaluasi_akip') {
                return redirect('/akip/dashboard');
            } else if ($request->modul == 'zi') {
                return redirect('/zi/dashboard');
            } else {
                return redirect('/dashboard');
            };
        }

        throw \Illuminate\Validation\ValidationException::withMessages([
            'username' => __('auth.failed'),
        ]);


        return redirect()->intended(RouteServiceProvider::HOME);
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

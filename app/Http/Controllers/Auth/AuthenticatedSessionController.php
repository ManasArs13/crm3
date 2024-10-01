<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
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
        $request->authenticate();

        $user = Auth::user();

        if ($user->current_session_id && $user->current_session_id !== session()->getId()) {

            Auth::logoutOtherDevices($request->input('password'));

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Вы уже вошли в систему с другого устройства.',
            ]);
        }

        $user->update(['current_session_id' => session()->getId()]);

        $request->session()->regenerate();

//        return redirect()->intended(RouteServiceProvider::HOME);

        if (Auth::user()->hasRole('admin')) {
            return redirect('/dashboard');
        }
        if (Auth::user()->hasRole('operator')) {
            return redirect('/operator/shipments');
        }
        if (Auth::user()->hasRole('manager')) {
            return redirect('/dashboard');
        }
        if (Auth::user()->hasRole('dispatcher')) {
            return redirect('/dashboard');
        }
        if (Auth::user()->hasRole('audit')) {
            return redirect('/dashboard');
        }
//        if (Auth::user()->hasRole('carrier')) {
//            return redirect('/operator/shipments');
//        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user) {
            $user->update(['current_session_id' => null]);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

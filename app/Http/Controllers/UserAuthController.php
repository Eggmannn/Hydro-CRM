<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('company.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $remember = $request->filled('remember');
        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            Auth::guard('crd_admin')->logout();
            $user = Auth::guard('web')->user();
            if ($user && method_exists($user, 'isCustomerAdmin') && $user->isCustomerAdmin()) {
                return redirect()->intended(route('customer-admin.dashboard'));
            }
            return redirect()->intended(route('dashboard'));
        }
        return back()
            ->withErrors(['email' => 'Invalid credentials.'])
            ->withInput($request->only('email', 'remember'));
    }


    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CrdAdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('crd_admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $remember = $request->filled('remember');
        if (Auth::guard('crd_admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            Auth::guard('web')->logout();
            return redirect()->intended(route('crd_admin.dashboard'));
        }
        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput($request->only('email', 'remember'));
    }


    public function logout(Request $request)
    {
        Auth::guard('crd_admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/crd-admin/login');
    }
}

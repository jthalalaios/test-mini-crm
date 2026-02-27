<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

     public function login(LoginRequest $request)
    {
        $validated_data = $request->validated();
        $remember = $request->has('remember');

        if (Auth::attempt($validated_data, $remember)) {
            $request->session()->regenerate();
            return redirect('/companies');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        //$request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
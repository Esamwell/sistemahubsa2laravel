<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function me(Request $request)
    {
        $data = $this->getData();
        $users = $data['users'] ?? [];
        
        $user = collect($users)->first(function($user) use ($request) {
            return $user['token'] === $request->bearerToken();
        });
        
        if (!$user) {
            return response()->json(['error' => 'Não autorizado'], 401);
        }
        
        return response()->json($user);
    }
} 
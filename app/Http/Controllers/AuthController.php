<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Tampilkan form login
    public function showLogin()
    {
        // Kalau sudah login, redirect ke home
        if (Auth::check()) {
            return redirect()->route('home');
        }
        
        return view('auth.login');
    }

// Di AuthController.php, method login()
public function login(Request $request)
{
    try {
        // Validasi input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Cari user
        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return back()->withErrors([
                'login' => 'Username atau password salah.',
            ])->withInput($request->only('username'));
        }

        // Cek password (plain text untuk sementara)
        if ($user->password === $request->password) {
            Auth::login($user, $request->has('remember'));
            
            // REGENERATE SESSION (penting!)
            $request->session()->regenerate();

            // Redirect berdasarkan role
            if ($user->role === 'executive') {
                return redirect()->route('executive.dashboard')
                    ->with('success', 'Selamat datang, ' . $user->username . '!');
            } elseif ($user->role === 'production') {
                return redirect()->route('production.dashboard')
                    ->with('success', 'Selamat datang, ' . $user->username . '!');
            } else {
                return redirect()->route('home')
                    ->with('success', 'Selamat datang, ' . $user->username . '!');
            }
        }

        return back()->withErrors([
            'login' => 'Username atau password salah.',
        ])->withInput($request->only('username'));

    } catch (\Exception $e) {
        // Kalau ada error (termasuk CSRF)
        return redirect()->route('login')
            ->with('error', 'Session expired. Silakan login lagi.');
    }
}



    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Anda telah logout.');
    }
}
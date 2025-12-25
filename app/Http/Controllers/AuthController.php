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
        
        return back()->with('openLoginModal', true);
    }

// Di AuthController.php, method login()
public function login(Request $request)
{
    try {
        // 1. Validasi input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Gunakan Auth::attempt (Ini cara paling benar & aman di Laravel)
        // Fungsi ini otomatis mencari user, melakukan Hash check, dan membuat session.
        $credentials = [
            'username' => $request->username,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials, $request->has('remember'))) {
            
            // REGENERATE SESSION (penting!)
            $request->session()->regenerate();

            $user = Auth::user();

            // 3. Redirect berdasarkan role
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

        // Jika Auth::attempt gagal
        return back()->withErrors([
            'login' => 'Username atau password salah.',
        ])->withInput($request->only('username'));

    } catch (\Exception $e) {
        // Log error jika diperlukan untuk debugging
        return redirect()->route('login')
            ->with('error', 'Terjadi kesalahan sistem atau session expired.');
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
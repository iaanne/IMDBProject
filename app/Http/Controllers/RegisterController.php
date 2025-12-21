<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:4|confirmed', // butuh field password_confirmation di form
            'token'    => 'nullable|string' // Token bersifat opsional
        ]);

        // 2. Tentukan Role Berdasarkan Token
        $role = 'native'; // Default role
        $inputToken = $request->input('token');

        // Daftar Token Rahasia (Bisa diganti sesuka hati)
        $secretTokens = [
            'EXEC-2025' => 'executive',
            'PROD-2025' => 'production'
        ];

        // Cek logika token
        if ($inputToken) {
            if (array_key_exists($inputToken, $secretTokens)) {
                $role = $secretTokens[$inputToken];
            } else {
                // Jika token diisi tapi salah, kembalikan error
                return back()->withErrors(['token' => 'Token akses tidak valid! Kosongkan jika user biasa.'])->withInput();
            }
        }

        // 3. Simpan User ke Database
        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => $role,
        ]);

        // 4. Langsung Login Otomatis setelah register
        Auth::login($user);

        // 5. Redirect ke Home dengan pesan sukses
        return redirect('/')->with('success', "Selamat datang, $user->username! Akun Anda ($role) berhasil dibuat.");
    }
}
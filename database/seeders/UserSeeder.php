<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 1. Native User
        User::create([
            'username' => 'native_user',
            'password' => Hash::make('native123'), // Password otomatis di-hash aman
            'role' => 'native',
        ]);

        // 2. Executive User
        User::create([
            'username' => 'executive_user',
            'password' => Hash::make('executive123'),
            'role' => 'executive',
        ]);

        // 3. Production User
        User::create([
            'username' => 'production_user',
            'password' => Hash::make('production123'),
            'role' => 'production',
        ]);
    }
}
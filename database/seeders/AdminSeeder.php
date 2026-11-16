<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Membuat/mengangkat akun admin panel (/admin). Kredensial dipakai dari
 * variabel lingkungan bila tersedia (produksi); default hanya untuk DEMO.
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = (string) env('ADMIN_EMAIL', 'admin@alwariscom.online');
        $name = (string) env('ADMIN_NAME', 'Admin Al-Waris');
        $password = (string) env('ADMIN_PASSWORD', 'Alwaris-Admin#2026');

        $admin = User::query()->firstOrNew(['email' => $email]);
        $admin->fill([
            'name' => $name,
            'password' => Hash::make($password),
            'is_admin' => true,
        ]);
        $admin->save();
    }
}

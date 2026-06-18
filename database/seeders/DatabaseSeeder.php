<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Panggil seeder lain secara berurutan
        $this->call([
            BranchSeeder::class, // Mengeksekusi Kos ID 1 (Sukolilo) dan Kos ID 2 (Rungkut)
            KamarSeeder::class,  // Mengeksekusi data kamar
        ]);

        // 2. Buat User Super Admin
        User::create([
            'username' => 'superadmin',
            'nama' => 'Super Admin PuluBoys',
            'email' => 'super@puluboys.com',
            'no_wa' => '081111111111',
            'password' => Hash::make('super123'), 
            'role' => 'super_admin',
            'kos_id' => null,   
            'kamar_id' => null,
        ]);

        // 3. Buat User Admin Rungkut (Cabang 2)
        User::create([
            'username' => 'adminrungkut',
            'nama' => 'Admin Rungkut',
            'email' => 'rungkut@puluboys.com',
            'no_wa' => '082222222222',
            'password' => Hash::make('admin123'), 
            'role' => 'admin_cabang',
            'kos_id' => 2, 
            'kamar_id' => null,
        ]);

        // ==========================================
        // 4. INI YANG DITAMBAHKAN: Admin Sukolilo (Cabang 1)
        // ==========================================
        User::create([
            'username' => 'adminsukolilo',
            'nama' => 'Admin Sukolilo',
            'email' => 'sukolilo@puluboys.com',
            'no_wa' => '083333333333',
            'password' => Hash::make('admin123'), 
            'role' => 'admin_cabang',
            'kos_id' => 1, 
            'kamar_id' => null,
        ]);
    }
}
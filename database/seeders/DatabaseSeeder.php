<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kos;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $kosRungkut = Kos::create([
            'nama' => 'PuluBoys Rungkut',
            'alamat' => 'Jl. Rungkut Madya No. 10, Surabaya',
        ]);

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

        User::create([
            'username' => 'adminrungkut',
            'nama' => 'Admin Rungkut',
            'email' => 'rungkut@puluboys.com',
            'no_wa' => '082222222222',
            'password' => Hash::make('admin123'), 
            'role' => 'admin_cabang',
            'kos_id' => $kosRungkut->id, 
            'kamar_id' => null,
        ]);
    }
}
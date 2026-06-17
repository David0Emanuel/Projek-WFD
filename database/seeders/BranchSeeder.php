<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BranchSeeder extends Seeder
{
    public function run()
    {

        Schema::disableForeignKeyConstraints();
        // Kosongkan tabel dulu agar tidak duplikat jika dijalankan ulang
        DB::table('kos')->truncate();

        DB::table('kos')->insert([
            [
                'id' => 1,
                'nama' => 'Kos PuluBoys - Sukolilo',
                'alamat' => 'Jl. Keputih Sukolilo No. 10, Surabaya',
                'deskripsi' => 'Kos nyaman dekat kampus dengan fasilitas lengkap dan akses 24 jam.',
                'foto' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nama' => 'Kos PuluBoys - Rungkut',
                'alamat' => 'Jl. Rungkut Madya No. 45, Surabaya',
                'deskripsi' => 'Akses mudah ke area industri dan kampus UPN, lingkungan tenang.',
                'foto' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Schema::enableForeignKeyConstraints();
    }
}

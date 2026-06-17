<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class KamarSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('kamars')->truncate();

        DB::table('kamars')->insert([
            // Kamar untuk Branch 1 (Sukolilo)
            ['kos_id' => 1, 'nomor' => 'A1', 'tipe_kamar' => 'single', 'harga' => 800000, 'status' => 'Kosong', 'created_at' => now()],
            ['kos_id' => 1, 'nomor' => 'A2', 'tipe_kamar' => 'single', 'harga' => 800000, 'status' => 'Kosong', 'created_at' => now()],
            ['kos_id' => 1, 'nomor' => 'B1', 'tipe_kamar' => 'double', 'harga' => 1200000, 'status' => 'terisi', 'created_at' => now()],
            
            // Kamar untuk Branch 2 (Rungkut)
            ['kos_id' => 2, 'nomor' => 'R1', 'tipe_kamar' => 'single', 'harga' => 750000, 'status' => 'Kosong', 'created_at' => now()],
            ['kos_id' => 2, 'nomor' => 'R2', 'tipe_kamar' => 'double', 'harga' => 1100000, 'status' => 'Kosong', 'created_at' => now()],
        ]);

        Schema::enableForeignKeyConstraints();

    }
}

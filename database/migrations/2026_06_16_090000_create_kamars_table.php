<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kamars', function (Blueprint $table) {
            $table->id(); // Ini menghasilkan BIGINT UNSIGNED (Sangat cocok dengan foreignId)
            $table->string('nomor_kamar');
            $table->string('tipe_kamar'); // Contoh: Reguler, VIP
            $table->decimal('harga_bulanan', 15, 2);
            $table->string('status')->default('Kosong'); // Kosong, Terisi, Booking
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kamars');
    }
};
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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel User (Tenant/Visitor) dan Kamar
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kamar_id')->constrained('kamars')->onDelete('cascade');
            
            // Logika Transaksi & Invoice
            $table->decimal('total', 15, 2); // Nominal tagihan
            $table->string('status_transaksi')->default('Unpaid'); // Unpaid, Paid, Expired, Cancelled
            $table->string('type'); // Contoh: 'DP Booking' atau 'Bulanan'
            $table->timestamp('expired_at')->nullable(); // Batas waktu bayar (misal 2 jam untuk DP)
            
            // Logika Bukti Meteran (Listrik/Air) -> Nullable karena DP tidak butuh meteran
            $table->integer('angka_meteran')->nullable(); // Input angka meteran oleh admin
            $table->string('foto_meteran')->nullable(); // Path string tempat menyimpan foto di storage
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
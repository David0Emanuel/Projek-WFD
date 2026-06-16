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
            $table->id(); 
            // REVISI: Menambahkan foreign key kos_id sesuai relasi proposal 
            $table->foreignId('kos_id')->constrained('kos')->onDelete('cascade'); 
            
            // REVISI: Disesuaikan namanya dengan ERD (nomor_kamar -> nomor) [cite: 57]
            $table->string('nomor'); 
            $table->string('tipe_kamar'); 
            
            // REVISI: Disesuaikan namanya dengan ERD (harga_bulanan -> harga) [cite: 57]
            $table->decimal('harga', 15, 2); 
            
            $table->string('status')->default('Kosong'); 
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

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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Primary Key 
            $table->string('username')->unique(); // Untuk kebutuhan Login [cite: 11, 13]
            $table->string('nama')->nullable(); // Opsional saat registrasi awal 
            $table->string('email')->unique()->nullable(); // Disesuaikan menjadi nullable 
            $table->timestamp('email_verified_at')->nullable();
            $table->string('no_wa'); // Wajib untuk formulir pendaftaran [cite: 13, 23]
            $table->string('password'); // Keamanan Autentikasi [cite: 11, 13]
            
            // Konfigurasi 4 Role Sistem
            $table->enum('role', ['visitor', 'tenant', 'admin_cabang', 'super_admin'])->default('visitor'); // 

            // Relasi tabel (dibuat nullable sesuai panduan ERD)
            $table->foreignId('kos_id')->nullable(); // Opsional untuk mapping admin_cabang [cite: 23, 79]
            $table->foreignId('kamar_id')->nullable()->unique(); // Menyimpan data kamar tenant, aturan 1 kamar 1 orang [cite: 23, 96, 97]

            // Logika durasi sewa
            $table->date('tanggal_mulaiSewa')->nullable(); // 
            $table->date('tanggal_selesaiSewa')->nullable(); // 

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
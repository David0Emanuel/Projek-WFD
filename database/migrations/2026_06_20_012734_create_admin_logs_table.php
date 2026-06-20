<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up()
    {
        Schema::create('admin_logs', function (Blueprint $table) {
            $table->id();
            $table->string('tipe'); // 'login', 'pembayaran', atau 'survey'
            $table->text('pesan');
            $table->boolean('is_read')->default(false); // Untuk penanda notifikasi baru
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_logs');
    }
};

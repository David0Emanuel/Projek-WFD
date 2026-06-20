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
        Schema::table('kamars', function (Blueprint $table) {
            $table->string('foto_kamar')->nullable();
            $table->text('spesifikasi')->nullable();
            $table->text('fasilitas')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('kamars', function (Blueprint $table) {
            $table->dropColumn(['foto_kamar', 'spesifikasi', 'fasilitas']);
        });
    }
};

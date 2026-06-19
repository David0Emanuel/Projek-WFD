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
       Schema::create('pengumumans', function (Blueprint $table) {
        $table->id();
        $table->string('judul');
        $table->text('isi');
        // kos_id bernilai null jika pengumuman global (untuk semua cabang)
        $table->unsignedBigInteger('kos_id')->nullable(); 
        $table->timestamps();

        $table->foreign('kos_id')->references('id')->on('kos')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumumen');
    }
};

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
        Schema::create('kegiatans', function (Blueprint $col) {
            $col->id();
            $col->string('nama_kegiatan');
            $col->string('slug')->unique();
            $col->date('tanggal_mulai');
            $col->date('tanggal_selesai')->nullable();
            $col->string('lokasi');
            $col->string('kategori');
            $col->text('deskripsi')->nullable();
            $col->boolean('is_published')->default(false);
            $col->foreignId('user_id')->constrained()->onDelete('cascade');
            $col->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatans');
    }
};

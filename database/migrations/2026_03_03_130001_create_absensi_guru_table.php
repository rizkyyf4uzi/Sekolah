<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi_guru', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('guru_id');
            $table->date('tanggal');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpa']);
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('guru_id')
                ->references('id')
                ->on('gurus')
                ->onDelete('cascade');

            $table->unique(['guru_id', 'tanggal'], 'absensi_guru_unique_guru_tanggal');
            $table->index(['tanggal'], 'absensi_guru_tanggal_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi_guru');
    }
};


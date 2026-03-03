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
        Schema::table('siswas', function (Blueprint $table) {
            // Data Calon Siswa tambahan
            $table->integer('anak_ke')->nullable();
            $table->string('bahasa_sehari_hari')->nullable();
            $table->integer('jarak_rumah_ke_sekolah')->nullable(); // Dalam meter
            $table->integer('waktu_tempuh_ke_sekolah')->nullable(); // Dalam menit
            $table->string('tinggal_bersama')->nullable();
            $table->string('status_tempat_tinggal')->nullable();

            // Alamat KK (jika berbeda dengan domisili)
            $table->string('provinsi_kk')->nullable();
            $table->string('kota_kabupaten_kk')->nullable();
            $table->string('kecamatan_kk')->nullable();
            $table->string('kelurahan_kk')->nullable();
            $table->string('nama_jalan_kk')->nullable();
            $table->text('alamat_kk')->nullable();

            // Data Wali tambahan
            $table->string('tempat_lahir_wali')->nullable();
            $table->date('tanggal_lahir_wali')->nullable();
            $table->string('pendidikan_wali')->nullable();
            $table->string('email_wali')->nullable();

            // Additional PPDB Info
            $table->string('jenis_daftar')->nullable();
            $table->string('sumber_informasi_ppdb')->nullable();
            $table->string('punya_saudara_sekolah_tk')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn([
                'anak_ke', 'bahasa_sehari_hari', 'jarak_rumah_ke_sekolah', 
                'waktu_tempuh_ke_sekolah', 'tinggal_bersama', 'status_tempat_tinggal',
                'provinsi_kk', 'kota_kabupaten_kk', 'kecamatan_kk', 
                'kelurahan_kk', 'nama_jalan_kk', 'alamat_kk',
                'tempat_lahir_wali', 'tanggal_lahir_wali', 'pendidikan_wali', 'email_wali',
                'jenis_daftar', 'sumber_informasi_ppdb', 'punya_saudara_sekolah_tk'
            ]);
        });
    }
};

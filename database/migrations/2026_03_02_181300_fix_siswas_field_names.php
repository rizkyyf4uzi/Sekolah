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
            // Rename to match form and spec
            $table->renameColumn('no_hp_ayah', 'nomor_telepon_ayah');
            $table->renameColumn('no_hp_ibu', 'nomor_telepon_ibu');
            $table->renameColumn('penghasilan_ayah', 'penghasilan_per_bulan_ayah');
            $table->renameColumn('penghasilan_ibu', 'penghasilan_per_bulan_ibu');
            
            // Remove fields requested to be deleted
            if (Schema::hasColumn('siswas', 'bidang_pekerjaan_ayah')) {
                $table->dropColumn('bidang_pekerjaan_ayah');
            }
            if (Schema::hasColumn('siswas', 'bidang_pekerjaan_ibu')) {
                $table->dropColumn('bidang_pekerjaan_ibu');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->renameColumn('nomor_telepon_ayah', 'no_hp_ayah');
            $table->renameColumn('nomor_telepon_ibu', 'no_hp_ibu');
            $table->renameColumn('penghasilan_per_bulan_ayah', 'penghasilan_ayah');
            $table->renameColumn('penghasilan_per_bulan_ibu', 'penghasilan_ibu');
            
            $table->string('bidang_pekerjaan_ayah', 100)->nullable();
            $table->string('bidang_pekerjaan_ibu', 100)->nullable();
        });
    }
};

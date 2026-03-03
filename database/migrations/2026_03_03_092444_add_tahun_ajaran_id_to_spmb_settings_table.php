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
        Schema::table('spmb_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('spmb_settings', 'tahun_ajaran_id')) {
                $table->foreignId('tahun_ajaran_id')->nullable()->after('id')->constrained('tahun_ajarans')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('spmb_settings', function (Blueprint $table) {
            $table->dropForeign(['tahun_ajaran_id']);
            $table->dropColumn('tahun_ajaran_id');
        });
    }
};

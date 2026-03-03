<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

use App\Models\Siswa;
use Illuminate\Support\Facades\Schema;

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$siswa = Siswa::find(22);

if (!$siswa) {
    echo "Siswa ID 22 tidak ditemukan!\n";
    exit(1);
}

echo "VERIFIKASI DATA SISWA\n";
echo "=====================\n";
echo "ID: " . $siswa->id . "\n";
echo "Nama: " . $siswa->nama_lengkap . "\n";
echo "Status: " . $siswa->status_siswa . "\n";
echo "Kelompok: " . $siswa->kelompok . "\n";
echo "NIS: " . ($siswa->nis ?? 'null') . "\n";
echo "NISN: " . ($siswa->nisn ?? 'null') . "\n";
echo "Bahasa: " . ($siswa->bahasa_sehari_hari ?? 'null') . "\n";
echo "Jarak: " . ($siswa->jarak_rumah_ke_sekolah ?? 'null') . "\n";
echo "Waktu: " . ($siswa->waktu_tempuh_ke_sekolah ?? 'null') . "\n";
echo "Punya Wali: " . ($siswa->punya_wali ? 'Ya' : 'Tidak') . "\n";
echo "Hubungan Wali: " . ($siswa->hubungan_dengan_anak ?? 'null') . "\n";
echo "\nVerifikasi Kolom Model:\n";
$fillable = $siswa->getFillable();
echo "Fillable: " . implode(', ', $fillable) . "\n";

echo "\nSEMUA OK\n";

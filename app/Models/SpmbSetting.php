<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SpmbSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun_ajaran_id',
        'tahun_ajaran',
        'gelombang',
        'pendaftaran_mulai',
        'pendaftaran_selesai',
        'pengumuman_mulai',
        'pengumuman_selesai',
        'countdown_mulai',
        'countdown_durasi',
        'kuota_zonasi',
        'kuota_afirmasi',
        'kuota_prestasi',
        'kuota_mutasi',
        'status_pendaftaran',
        'status_pengumuman',
        'is_published',
        'published_at',
        'published_by',
        'pesan_tunggu',
        'pesan_selesai',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'pendaftaran_mulai' => 'datetime',
        'pendaftaran_selesai' => 'datetime',
        'pengumuman_mulai' => 'datetime',
        'pengumuman_selesai' => 'datetime',
        'countdown_mulai' => 'datetime',
        'published_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    /**
     * CEK APAKAH PENGUMUMAN BISA DITAMPILKAN
     * Prioritas: Manual Publish > Waktu Otomatis
     */
    public function isPengumumanTampil()
    {
        // Prioritas 1: Cek manual publish
        if ($this->is_published) {
            return true;
        }

        // Prioritas 2: Cek waktu otomatis
        if (!$this->pengumuman_mulai) {
            return false;
        }

        $now = now();
        return $now->greaterThanOrEqualTo($this->pengumuman_mulai);
    }

    /**
     * CEK APAKAH MASIH DALAM COUNTDOWN
     */
    public function isCountdownAktif()
    {
        // Jika sudah manual publish, countdown langsung selesai
        if ($this->is_published) {
            return false;
        }

        // Jika belum waktunya pengumuman
        if (!$this->pengumuman_mulai) {
            return false;
        }

        $now = now();
        
        // Countdown aktif jika belum sampai waktu pengumuman_mulai
        return $now->lessThan($this->pengumuman_mulai);
    }

    /**
     * HITUNG SISA WAKTU COUNTDOWN (dalam detik)
     */
    public function getCountdownSisa()
    {
        if (!$this->isCountdownAktif()) {
            return 0;
        }

        $now = now();
        return $now->diffInSeconds($this->pengumuman_mulai, false);
    }

    /**
     * GET STATUS PENGUMUMAN UNTUK HOMEPAGE
     */
    public function getStatusPengumumanHomepage()
    {
        $now = now();

        // 1. Belum ada setting pengumuman_mulai
        if (!$this->pengumuman_mulai) {
            return [
                'status' => 'belum_tersedia',
                'message' => 'Pengumuman belum tersedia',
                'show_countdown' => false,
                'show_hasil' => false,
                'countdown_target' => null
            ];
        }

        // 2. Sudah manual publish
        if ($this->is_published) {
            return [
                'status' => 'published',
                'message' => 'Pengumuman Kelulusan Sudah Dibuka',
                'show_countdown' => false,
                'show_hasil' => true,
                'countdown_target' => null,
                'published_at' => $this->published_at ? $this->published_at->translatedFormat('d F Y, H:i') : null
            ];
        }

        // 3. Belum sampai waktu pengumuman (countdown aktif)
        if ($now->lessThan($this->pengumuman_mulai)) {
            return [
                'status' => 'countdown',
                'message' => 'Pengumuman akan dibuka pada:',
                'show_countdown' => true,
                'show_hasil' => false,
                'countdown_target' => $this->pengumuman_mulai->toIso8601String(),
                'target_date' => $this->pengumuman_mulai->translatedFormat('d F Y'),
                'target_time' => $this->pengumuman_mulai->translatedFormat('H:i')
            ];
        }

        // 4. Sudah sampai waktu (otomatis tampil)
        return [
            'status' => 'otomatis_tampil',
            'message' => 'Pengumuman Kelulusan',
            'show_countdown' => false,
            'show_hasil' => true,
            'countdown_target' => null
        ];
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Siswa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'siswas';
    protected $fillable = [
        // Relasi
        'spmb_id',
        
        // Nomor Induk
        'nis',
        'nisn',
        
        // Data siswa
        'nik',
        'nama_lengkap',
        'nama_panggilan',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'foto',
        'anak_ke',
        'bahasa_sehari_hari',
        'tinggal_bersama',
        'status_tempat_tinggal',
        
        // Alamat domisili
        'alamat',
        'provinsi',
        'kota_kabupaten',
        'kecamatan',
        'kelurahan',
        'nama_jalan',
        
        // Alamat KK (jika berbeda)
        'provinsi_kk',
        'kota_kabupaten_kk',
        'kecamatan_kk',
        'kelurahan_kk',
        'nama_jalan_kk',
        'alamat_kk',
        
        // Data kesehatan
        'berat_badan',
        'tinggi_badan',
        'jarak_rumah_ke_sekolah',
        'waktu_tempuh_ke_sekolah',
        'golongan_darah',
        'penyakit_pernah_diderita',
        'imunisasi',
        
        // Data ayah
        'nama_ayah',
        'nik_ayah',
        'tempat_lahir_ayah',
        'tanggal_lahir_ayah',
        'pendidikan_ayah',
        'pekerjaan_ayah',
        'penghasilan_per_bulan_ayah',
        
        // Data ibu
        'nama_ibu',
        'nik_ibu',
        'tempat_lahir_ibu',
        'tanggal_lahir_ibu',
        'pendidikan_ibu',
        'pekerjaan_ibu',
        'penghasilan_per_bulan_ibu',
        
        // Data wali
        'punya_wali',
        'nama_wali',
        'nik_wali',
        'pekerjaan_wali',
        'nomor_telepon_wali',
        'tempat_lahir_wali',
        'tanggal_lahir_wali',
        'pendidikan_wali',
        'email_wali',
        'hubungan_dengan_anak',
        
        // Kontak
        'nomor_telepon_ayah',
        'nomor_telepon_ibu',
        'no_hp_ortu',
        'email_ortu',
        
        // Informasi akademik
        'kelompok',
        'tahun_ajaran_id',
        'tahun_ajaran',
        'status_siswa', // aktif, lulus, pindah, cuti
        'tanggal_masuk',
        'tanggal_keluar',
        'jalur_masuk',
        'jenis_daftar',
        'sumber_informasi_ppdb',
        'punya_saudara_sekolah_tk',
        
        // Informasi kelas
        'kelas',
        'guru_kelas',
        
        // Catatan
        'catatan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_lahir_ayah' => 'date',
        'tanggal_lahir_ibu' => 'date',
        'tanggal_lahir_wali' => 'date',
        'tanggal_masuk' => 'date',
        'tanggal_keluar' => 'date',
        'punya_wali' => 'boolean',
        'berat_badan' => 'decimal:2',
        'tinggi_badan' => 'decimal:2',
    ];

    // Constanta untuk status siswa
    const STATUS_AKTIF = 'aktif';
    const STATUS_LULUS = 'lulus';
    const STATUS_PINDAH = 'pindah';
    const STATUS_CUTI = 'cuti';

    // Array status yang tersedia
    const STATUSES = [
        self::STATUS_AKTIF => 'Aktif',
        self::STATUS_LULUS => 'Lulus',
        self::STATUS_PINDAH => 'Pindah',
        self::STATUS_CUTI => 'Cuti',
    ];

    /* =======================
     | RELATIONSHIPS
     ======================= */
    
    /**
     * Relasi ke SPMB (pendaftaran asal)
     */
    public function spmb()
    {
        return $this->belongsTo(Spmb::class, 'spmb_id');
    }

    /**
     * Relasi ke Tahun Ajaran
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    /**
     * Relasi ke Absensi
     */
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'siswa_id');
    }

    /**
     * Relasi ke Nilai
     */
    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'siswa_id');
    }

    /* =======================
     | ACCESSORS
     ======================= */

    /**
     * Hitung usia siswa
     */
    public function getUsiaAttribute()
    {
        if (!$this->tanggal_lahir) {
            return 0;
        }

        return Carbon::parse($this->tanggal_lahir)->age;
    }

    /**
     * Usia dalam format tahun bulan
     */
    public function getUsiaLabelAttribute()
    {
        if (!$this->tanggal_lahir) {
            return '-';
        }

        $birthDate = Carbon::parse($this->tanggal_lahir);
        $now = Carbon::now();
        
        $tahun = $birthDate->diffInYears($now);
        $bulan = $birthDate->copy()->addYears($tahun)->diffInMonths($now);
        
        return "{$tahun} tahun {$bulan} bulan";
    }

    /**
     * Jenis kelamin lengkap
     */
    public function getJenisKelaminLengkapAttribute()
    {
        return $this->jenis_kelamin === 'L'
            ? 'Laki-laki'
            : 'Perempuan';
    }

    /**
     * Format tanggal lahir
     */
    public function getTanggalLahirFormattedAttribute()
    {
        return $this->tanggal_lahir
            ? $this->tanggal_lahir->translatedFormat('d F Y')
            : '-';
    }

    /**
     * Format tanggal masuk
     */
    public function getTanggalMasukFormattedAttribute()
    {
        return $this->tanggal_masuk
            ? $this->tanggal_masuk->translatedFormat('d F Y')
            : '-';
    }

    /**
     * Format tanggal keluar
     */
    public function getTanggalKeluarFormattedAttribute()
    {
        return $this->tanggal_keluar
            ? $this->tanggal_keluar->translatedFormat('d F Y')
            : '-';
    }

    /**
     * Nama lengkap (alias)
     */
    public function getNamaAttribute()
    {
        return $this->nama_lengkap;
    }

    /**
     * Alamat lengkap dari komponen
     */
    public function getAlamatLengkapAttribute()
    {
        $parts = [];
        
        if ($this->nama_jalan) $parts[] = $this->nama_jalan;
        if ($this->kelurahan) $parts[] = 'Kel. ' . $this->kelurahan;
        if ($this->kecamatan) $parts[] = 'Kec. ' . $this->kecamatan;
        if ($this->kota_kabupaten) $parts[] = $this->kota_kabupaten;
        if ($this->provinsi) $parts[] = $this->provinsi;
        
        return !empty($parts) ? implode(', ', $parts) : $this->alamat;
    }

    /**
     * Nama orang tua (gabungan)
     */
    public function getNamaOrtuAttribute()
    {
        return $this->nama_ayah . ' & ' . $this->nama_ibu;
    }

    /**
     * Label status siswa
     */
    public function getStatusLabelAttribute()
    {
        return self::STATUSES[$this->status_siswa] ?? $this->status_siswa;
    }

    /**
     * Status badge color
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status_siswa) {
            self::STATUS_AKTIF => 'bg-green-100 text-green-800 border-green-200',
            self::STATUS_LULUS => 'bg-blue-100 text-blue-800 border-blue-200',
            self::STATUS_PINDAH => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            self::STATUS_CUTI => 'bg-purple-100 text-purple-800 border-purple-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200',
        };
    }

    /**
     * Status badge dengan ikon
     */
    public function getStatusBadgeWithIconAttribute()
    {
        $icon = match($this->status_siswa) {
            self::STATUS_AKTIF => '<i class="fas fa-check-circle mr-1"></i>',
            self::STATUS_LULUS => '<i class="fas fa-graduation-cap mr-1"></i>',
            self::STATUS_PINDAH => '<i class="fas fa-exchange-alt mr-1"></i>',
            self::STATUS_CUTI => '<i class="fas fa-clock mr-1"></i>',
            default => '<i class="fas fa-question-circle mr-1"></i>',
        };
        
        return '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ' . $this->status_badge . '">' . $icon . $this->status_label . '</span>';
    }

    /* =======================
     | SCOPES
     ======================= */

    /**
     * Scope siswa aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status_siswa', self::STATUS_AKTIF);
    }

    /**
     * Scope siswa lulus
     */
    public function scopeLulus($query)
    {
        return $query->where('status_siswa', self::STATUS_LULUS);
    }

    /**
     * Scope siswa pindah
     */
    public function scopePindah($query)
    {
        return $query->where('status_siswa', self::STATUS_PINDAH);
    }

    /**
     * Scope siswa cuti
     */
    public function scopeCuti($query)
    {
        return $query->where('status_siswa', self::STATUS_CUTI);
    }

    /**
     * Scope by status
     */
    public function scopeByStatus($query, $status)
    {
        if ($status && in_array($status, array_keys(self::STATUSES))) {
            return $query->where('status_siswa', $status);
        }
        return $query;
    }

    /**
     * Scope by kelompok
     */
    public function scopeByKelompok($query, $kelompok)
    {
        if ($kelompok) {
            return $query->where('kelompok', $kelompok);
        }
        return $query;
    }

    /**
     * Scope by tahun ajaran
     */
    public function scopeByTahunAjaran($query, $tahunAjaranId)
    {
        if ($tahunAjaranId) {
            return $query->where('tahun_ajaran_id', $tahunAjaranId);
        }
        return $query;
    }

    /**
     * Scope by jalur masuk
     */
    public function scopeByJalurMasuk($query, $jalurMasuk)
    {
        if ($jalurMasuk) {
            return $query->where('jalur_masuk', $jalurMasuk);
        }
        return $query;
    }

    /**
     * Scope pencarian
     */
    public function scopeSearch($query, $keyword)
    {
        if ($keyword) {
            return $query->where(function ($q) use ($keyword) {
                $q->where('nama_lengkap', 'like', "%{$keyword}%")
                  ->orWhere('nik', 'like', "%{$keyword}%")
                  ->orWhere('nis', 'like', "%{$keyword}%")
                  ->orWhere('nisn', 'like', "%{$keyword}%")
                  ->orWhere('nama_ayah', 'like', "%{$keyword}%")
                  ->orWhere('nama_ibu', 'like', "%{$keyword}%")
                  ->orWhere('no_hp_ortu', 'like', "%{$keyword}%");
            });
        }
        return $query;
    }

    /* =======================
     | METHODS
     ======================= */

    /**
     * Generate NIS otomatis
     */
    public static function generateNIS($tahunAjaranId)
    {
        $tahun = date('Y');
        $lastSiswa = self::whereYear('created_at', $tahun)
                        ->orderBy('nis', 'desc')
                        ->first();
        
        if ($lastSiswa && $lastSiswa->nis) {
            $lastNumber = intval(substr($lastSiswa->nis, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return 'NIS-' . $tahun . '-' . $newNumber;
    }

    /**
     * Update status siswa menjadi lulus
     */
    public function markAsLulus($tanggalKeluar = null)
    {
        $this->status_siswa = self::STATUS_LULUS;
        $this->tanggal_keluar = $tanggalKeluar ?? Carbon::now();
        return $this->save();
    }

    /**
     * Update status siswa menjadi pindah
     */
    public function markAsPindah($tanggalKeluar = null)
    {
        $this->status_siswa = self::STATUS_PINDAH;
        $this->tanggal_keluar = $tanggalKeluar ?? Carbon::now();
        return $this->save();
    }

    /**
     * Update status siswa menjadi cuti
     */
    public function markAsCuti()
    {
        $this->status_siswa = self::STATUS_CUTI;
        return $this->save();
    }

    /**
     * Update status siswa menjadi aktif kembali
     */
    public function markAsAktif()
    {
        $this->status_siswa = self::STATUS_AKTIF;
        $this->tanggal_keluar = null;
        return $this->save();
    }

    /**
     * Cek apakah siswa aktif
     */
    public function isAktif()
    {
        return $this->status_siswa === self::STATUS_AKTIF;
    }

    /**
     * Cek apakah siswa lulus
     */
    public function isLulus()
    {
        return $this->status_siswa === self::STATUS_LULUS;
    }

    /**
     * Cek apakah siswa pindah
     */
    public function isPindah()
    {
        return $this->status_siswa === self::STATUS_PINDAH;
    }

    /**
     * Cek apakah siswa cuti
     */
    public function isCuti()
    {
        return $this->status_siswa === self::STATUS_CUTI;
    }
}
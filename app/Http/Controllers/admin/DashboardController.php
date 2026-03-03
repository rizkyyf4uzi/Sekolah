<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\User;
use App\Models\Spmb;
use App\Models\TahunAjaran;
use App\Models\BukuTamu;
use App\Models\Absensi;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $tahunAjaranAktif = TahunAjaran::where('is_aktif', true)->first();
        $tahunAjaranId = $tahunAjaranAktif ? $tahunAjaranAktif->id : null;
        
        $total_siswa = Siswa::aktif()->count();
        $total_guru = Guru::count();
        $total_admin = User::where('role', 'admin')->count();
        
        // Total pendaftaran baru SPMB (Menunggu Verifikasi)
        $pendaftaran_baru = Spmb::where('status_pendaftaran', 'Menunggu Verifikasi')
            ->when($tahunAjaranId, function($query) use ($tahunAjaranId) {
                return $query->where('tahun_ajaran_id', $tahunAjaranId);
            })
            ->count();
        
        // Recent Pendaftaran SPMB (5 terbaru)
        $recent_pendaftaran = Spmb::with(['tahunAjaran'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Chart Siswa per Kelompok
        $chart_siswa = Siswa::aktif()
            ->select('kelompok', DB::raw('count(*) as total'))
            ->groupBy('kelompok')
            ->get();
        
        // Statistik SPMB per Status
        $spmb_statistics = [
            'menunggu' => Spmb::where('status_pendaftaran', 'Menunggu Verifikasi')
                ->when($tahunAjaranId, fn($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
                ->count(),
            'diterima' => Spmb::where('status_pendaftaran', 'Diterima')
                ->when($tahunAjaranId, fn($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
                ->count(),
            'mundur' => Spmb::where('status_pendaftaran', 'Mundur')
                ->when($tahunAjaranId, fn($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
                ->count(),
            'total' => Spmb::when($tahunAjaranId, fn($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))->count(),
        ];
        
        // Statistik per Jenis Daftar (Siswa Baru vs Pindahan)
        $jenis_daftar_statistics = Spmb::select('jenis_daftar', DB::raw('count(*) as total'))
            ->when($tahunAjaranId, fn($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
            ->groupBy('jenis_daftar')
            ->get();
        
        // Statistik per Jenis Kelamin
        $jk_statistics = Spmb::select('jenis_kelamin', DB::raw('count(*) as total'))
            ->when($tahunAjaranId, fn($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
            ->groupBy('jenis_kelamin')
            ->get();
        
        // Grafik pendaftaran per bulan (tahun ini)
        $grafik_bulanan = Spmb::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('created_at', date('Y'))
            ->when($tahunAjaranId, fn($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();
        
        // Statistik siswa aktif per kelompok
        $siswa_per_kelompok = Siswa::aktif()
            ->select('kelompok', DB::raw('count(*) as total'))
            ->groupBy('kelompok')
            ->get();
        
        // 5 siswa terbaru
        $siswa_terbaru = Siswa::with(['spmb'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Statistik untuk kartu dashboard
        $siswa_aktif = Siswa::aktif()->count();
        $siswa_lulus = Siswa::where('status_siswa', 'lulus')->count();
        $kelompok_a = Siswa::aktif()->where('kelompok', 'A')->count();
        $kelompok_b = Siswa::aktif()->where('kelompok', 'B')->count();
        $laki_laki = Siswa::aktif()->where('jenis_kelamin', 'Laki-laki')->count();
        $perempuan = Siswa::aktif()->where('jenis_kelamin', 'Perempuan')->count();
        $absensi_hari_ini = Absensi::whereDate('tanggal', today())->count();
        $persen_absen = $total_siswa > 0
            ? round(min(100, ($absensi_hari_ini / $total_siswa) * 100), 1)
            : 0;

        $stats = [
            'siswa_aktif' => $siswa_aktif,
            'siswa_lulus' => $siswa_lulus,
            'total_siswa' => $total_siswa,
            'total_guru' => $total_guru,
            'total_admin' => $total_admin,
            'pendaftaran_baru' => $pendaftaran_baru,
            'kelompok_a' => $kelompok_a,
            'kelompok_b' => $kelompok_b,
            'laki_laki' => $laki_laki,
            'perempuan' => $perempuan,
            'spmb_menunggu' => $spmb_statistics['menunggu'] ?? 0,
            'spmb_diterima' => $spmb_statistics['diterima'] ?? 0,
            'spmb_mundur' => $spmb_statistics['mundur'] ?? 0,
            'ppdb_total' => $spmb_statistics['total'] ?? 0,
            'absensi_hari_ini' => $absensi_hari_ini,
            'persen_absen' => $persen_absen,
        ];

        return view('admin.dashboard', compact(
            'total_siswa',
            'total_guru',
            'total_admin',
            'pendaftaran_baru',
            'recent_pendaftaran',
            'chart_siswa',
            'spmb_statistics',
            'jenis_daftar_statistics',
            'jk_statistics',
            'grafik_bulanan',
            'siswa_per_kelompok',
            'siswa_terbaru',
            'tahunAjaranAktif',
            'stats'
        ));
    }
    
    public function getSpmbStatistics()
    {
        $tahunAjaranAktif = TahunAjaran::where('is_aktif', true)->first();
        $tahunAjaranId = $tahunAjaranAktif ? $tahunAjaranAktif->id : null;
        
        $statistics = [
            'menunggu' => Spmb::where('status_pendaftaran', 'Menunggu Verifikasi')
                ->when($tahunAjaranId, fn($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
                ->count(),
            'diterima' => Spmb::where('status_pendaftaran', 'Diterima')
                ->when($tahunAjaranId, fn($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
                ->count(),
            'mundur' => Spmb::where('status_pendaftaran', 'Mundur')
                ->when($tahunAjaranId, fn($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
                ->count(),
            'total' => Spmb::when($tahunAjaranId, fn($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))->count(),
        ];
        
        return response()->json($statistics);
    }
    
    public function getRecentRegistrations()
    {
        $tahunAjaranAktif = TahunAjaran::where('is_aktif', true)->first();
        $tahunAjaranId = $tahunAjaranAktif ? $tahunAjaranAktif->id : null;
        
        $recent = Spmb::with(['tahunAjaran'])
            ->when($tahunAjaranId, fn($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($spmb) {
                $statusIcon = '';
                $statusColor = '';
                
                switch($spmb->status_pendaftaran) {
                    case 'Diterima':
                        $statusIcon = 'fa-check-circle';
                        $statusColor = 'green';
                        break;
                    case 'Menunggu Verifikasi':
                        $statusIcon = 'fa-clock';
                        $statusColor = 'yellow';
                        break;
                    case 'Mundur':
                        $statusIcon = 'fa-times-circle';
                        $statusColor = 'red';
                        break;
                    default:
                        $statusIcon = 'fa-question-circle';
                        $statusColor = 'gray';
                }
                
                return [
                    'id' => $spmb->id,
                    'nama' => $spmb->nama_lengkap_anak ?? 'N/A',
                    'no_pendaftaran' => $spmb->no_pendaftaran,
                    'status' => $spmb->status_pendaftaran,
                    'status_icon' => $statusIcon,
                    'status_color' => $statusColor,
                    'tanggal' => $spmb->created_at->format('d/m/Y H:i'),
                    'tanggal_formatted' => $spmb->created_at->diffForHumans(),
                    'url' => route('admin.spmb.show', $spmb->id),
                ];
            });
        
        return response()->json($recent);
    }

    /**
     * Get Buku Tamu Statistics
     * Perbaikan: Menggunakan kolom yang benar (status_pendaftaran atau is_verified)
     */
    public function getBukuTamuStatistics()
    {
        // Cek dulu struktur tabel buku_tamu
        // Asumsi: tabel buku_tamu memiliki kolom 'status_pendaftaran' dan 'is_verified'
        // Jika tidak, sesuaikan dengan struktur yang ada
        
        try {
            $stats = [
                'total' => BukuTamu::count(),
                'today' => BukuTamu::whereDate('created_at', today())->count(),
                // Sesuaikan dengan kolom yang ada di tabel buku_tamu
                // Opsi 1: Jika menggunakan 'status_pendaftaran'
                'pending' => BukuTamu::where('status_pendaftaran', 'pending')->count(),
                // Opsi 2: Jika menggunakan 'is_verified' untuk status
                // 'pending' => BukuTamu::where('is_verified', false)->count(),
                'verified' => BukuTamu::where('is_verified', true)->count(),
            ];
        } catch (\Exception $e) {
            // Jika terjadi error, kembalikan data default
            $stats = [
                'total' => 0,
                'today' => 0,
                'pending' => 0,
                'verified' => 0,
            ];
        }
        
        return response()->json($stats);
    }
    
    /**
     * Get SPMB statistics by year
     */
    public function getSpmbStatisticsByYear($year = null)
    {
        $year = $year ?? date('Y');
        
        $statistics = Spmb::select(
                'status_pendaftaran',
                DB::raw('count(*) as total')
            )
            ->whereYear('created_at', $year)
            ->groupBy('status_pendaftaran')
            ->get();
        
        return response()->json($statistics);
    }
    
    /**
     * Get recent konversi ke siswa
     */
    public function getRecentKonversi()
    {
        $recent = Siswa::with(['spmb'])
            ->whereNotNull('spmb_id')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($siswa) {
                return [
                    'nama' => $siswa->nama_lengkap,
                    'kelompok' => $siswa->kelompok,
                    'tanggal' => $siswa->created_at->format('d/m/Y H:i'),
                    'url' => route('admin.siswa.show', $siswa->id)
                ];
            });
        
        return response()->json($recent);
    }

    /**
     * Optional: Method untuk mengecek struktur tabel buku_tamu
     * Bisa dipanggil sementara untuk debugging
     */
    public function checkBukuTamuStructure()
    {
        try {
            $columns = \Schema::getColumnListing('buku_tamus');
            return response()->json([
                'columns' => $columns,
                'sample' => BukuTamu::first()
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
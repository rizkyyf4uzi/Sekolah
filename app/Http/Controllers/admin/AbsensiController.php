<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        try {
            // ===== MODIFIED: GROUPED RESULTS FOR 100% PARITY WITH MOCKUP =====
            $query = Absensi::join('siswas', 'absensi.siswa_id', '=', 'siswas.id')
                ->leftJoin('tahun_ajarans', 'siswas.tahun_ajaran_id', '=', 'tahun_ajarans.id')
                ->select(
                    'absensi.tanggal',
                    'siswas.kelompok',
                    'tahun_ajarans.semester',
                    DB::raw('COUNT(CASE WHEN absensi.status = "hadir" THEN 1 END) as hadir'),
                    DB::raw('COUNT(CASE WHEN absensi.status = "sakit" THEN 1 END) as sakit'),
                    DB::raw('COUNT(CASE WHEN absensi.status = "izin" THEN 1 END) as izin'),
                    DB::raw('COUNT(CASE WHEN absensi.status = "alpa" THEN 1 END) as alpa'),
                    DB::raw('MAX(absensi.created_at) as latest_created_at')
                )
                ->groupBy('absensi.tanggal', 'siswas.kelompok', 'tahun_ajarans.semester')
                ->orderBy('absensi.tanggal', 'desc')
                ->orderBy('siswas.kelompok', 'asc');

            // FILTER KELOMPOK
            if ($request->filled('kelompok')) {
                $query->where('siswas.kelompok', $request->kelompok);
            }

            // FILTER TANGGAL
            if ($request->filled('tanggal')) {
                $query->whereDate('absensi.tanggal', $request->tanggal);
            } elseif ($request->filled('bulan')) {
                $query->whereYear('absensi.tanggal', Carbon::parse($request->bulan)->year)
                    ->whereMonth('absensi.tanggal', Carbon::parse($request->bulan)->month);
            }

            $absensi = $query->paginate(20)->withQueryString();
            
            // ===== STATISTIK GLOBAL =====
            $total_absensi = Absensi::count();
            $total_hadir = Absensi::where('status', 'hadir')->count();
            $total_izin_sakit = Absensi::whereIn('status', ['izin', 'sakit'])->count();
            $total_tidak_hadir = Absensi::where('status', 'alpa')->count();
            
            $persen_hadir = $total_absensi > 0 
                ? round(($total_hadir / $total_absensi) * 100, 1) 
                : 0;

            $status_distribution = [
                $total_hadir,
                Absensi::where('status', 'izin')->count(),
                Absensi::where('status', 'sakit')->count(),
                $total_tidak_hadir,
            ];

            return view('admin.absensi.index', [
                'absensi' => $absensi,
                'total_absensi' => $total_absensi,
                'total_hadir' => $total_hadir,
                'total_izin_sakit' => $total_izin_sakit,
                'total_tidak_hadir' => $total_tidak_hadir,
                'persen_hadir' => $persen_hadir,
                'status_distribution' => $status_distribution,
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in AbsensiController@index: ' . $e->getMessage());
            
            return view('admin.absensi.index', [
                'absensi' => collect(),
                'total_absensi' => 0,
                'total_hadir' => 0,
                'total_izin_sakit' => 0,
                'total_tidak_hadir' => 0,
                'persen_hadir' => 0,
                'status_distribution' => [0,0,0,0],
            ]);
        }
    }

    public function edit($id)
    {
        try {
            $absensi = Absensi::with(['siswa', 'guru'])->findOrFail($id);
            
            // KIRIM JUGA DAFTAR GURU PER KELOMPOK
            $guru_list = [];
            if ($absensi->siswa && $absensi->siswa->kelompok) {
                $guru_list = Guru::where('kelompok', $absensi->siswa->kelompok)->get();
            }
            
            return response()->json([
                'success' => true,
                'siswa_nama' => $absensi->siswa->nama_lengkap ?? $absensi->siswa->nama ?? 'Tidak ditemukan',
                'tanggal' => $absensi->tanggal,
                'status' => $absensi->status,
                'keterangan' => $absensi->keterangan,
                'guru_id' => $absensi->guru_id,
                'guru_list' => $guru_list
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in AbsensiController@edit: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit,alpa',
            'keterangan' => 'nullable|string|max:255'
        ]);

        try {
            $absensi = Absensi::findOrFail($id);
            $absensi->update([
                'tanggal' => $request->tanggal,
                'status' => $request->status,
                'keterangan' => $request->keterangan
            ]);

            return redirect()->route('admin.absensi.index')
                ->with('success', 'Data absensi berhasil diperbarui.');
        } catch (\Exception $e) {
            \Log::error('Error in AbsensiController@update: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $absensi = Absensi::findOrFail($id);
            $absensi->delete();

            return redirect()->route('admin.absensi.index')
                ->with('success', 'Data absensi berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('Error in AbsensiController@destroy: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:absensi,id'
            ]);

            Absensi::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' data absensi berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in AbsensiController@bulkDelete: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function detail(Request $request)
    {
        $tanggal = $request->get('tanggal', now()->format('Y-m-d'));
        $kelompok = $request->get('kelompok', '');

        $query = Siswa::with('tahunAjaran')->orderBy('nama_lengkap');
        if (!empty($kelompok)) {
            $query->where('kelompok', $kelompok);
        }
        $siswa = $query->get();
        $total = $siswa->count();

        $existing = Absensi::whereIn('siswa_id', $siswa->pluck('id'))
            ->whereDate('tanggal', $tanggal)
            ->get()
            ->keyBy('siswa_id');

        $stats = [
            'hadir' => $existing->where('status', 'hadir')->count(),
            'sakit' => $existing->where('status', 'sakit')->count(),
            'izin' => $existing->where('status', 'izin')->count(),
            'tidak_hadir' => $existing->where('status', 'alpa')->count(),
        ];

        return view('admin.absensi.detail', compact('siswa', 'tanggal', 'kelompok', 'existing', 'stats', 'total'));
    }

    /**
     * Show the bulk input (fill) form that lists students for a chosen date/kelompok
     */
    public function fill(Request $request)
    {
        $tanggal = $request->get('tanggal', now()->format('Y-m-d'));
        $kelompok = $request->get('kelompok', '');

        // Ambil siswa berdasarkan kelompok
        $query = Siswa::query()->orderBy('nama_lengkap');
        if (!empty($kelompok)) {
            $query->where('kelompok', $kelompok);
        }
        $siswa = $query->get();

        // AMBIL GURU YANG SESUAI KELOMPOK
        $guru = null;
        if (!empty($kelompok)) {
            $guru = Guru::where('kelompok', $kelompok)->first();
        }

        // existing absensi
        $existing = Absensi::whereIn('siswa_id', $siswa->pluck('id'))
            ->whereDate('tanggal', $tanggal)
            ->get()
            ->keyBy('siswa_id');

        return view('admin.absensi.fill', compact(
            'siswa', 
            'tanggal', 
            'kelompok', 
            'existing',
            'guru'
        ));
    }

    public function storeBatch(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kelompok' => 'nullable|string',
            'guru_id' => 'nullable|exists:gurus,id',
            'statuses' => 'required|array',
            'statuses.*' => 'in:hadir,izin,sakit,alpa',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $tanggal = $request->tanggal;
            
            $guruId = $request->guru_id;
            
            if (!$guruId && $request->kelompok) {
                $guru = Guru::where('kelompok', $request->kelompok)->first();
                $guruId = $guru ? $guru->id : null;
            }

            foreach ($request->statuses as $siswaId => $status) {
                $keterangan = $request->keterangan[$siswaId] ?? null;
                
                Absensi::updateOrCreate(
                    ['siswa_id' => $siswaId, 'tanggal' => $tanggal],
                    [
                        'status' => $status, 
                        'keterangan' => $keterangan, 
                        'guru_id' => $guruId
                    ]
                );
            }

            DB::commit();

            return redirect()->route('admin.absensi.index')
                ->with('success', 'Absensi berhasil disimpan untuk kelompok ' . $request->kelompok);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in AbsensiController@storeBatch: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan absensi: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        try {
            $query = Absensi::with(['siswa', 'guru'])
                ->orderBy('tanggal', 'desc');

            if ($request->has('kelompok') && $request->kelompok != '') {
                $query->whereHas('siswa', function($q) use ($request) {
                    $q->where('kelompok', $request->kelompok);
                });
            }

            $absensi = $query->get();

            // Create CSV file
            $filename = 'absensi-siswa-' . date('Y-m-d') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($absensi) {
                $file = fopen('php://output', 'w');
                
                // Header CSV
                fputcsv($file, [
                    'No',
                    'Nama Siswa',
                    'Kelompok',
                    'NIS',
                    'Tanggal',
                    'Status',
                    'Keterangan',
                    'Guru Pengajar',
                    'Waktu Input'
                ]);

                // Data CSV
                $no = 1;
                foreach ($absensi as $item) {
                    fputcsv($file, [
                        $no++,
                        $item->siswa->nama ?? 'Tidak ditemukan',
                        $item->siswa->kelompok ?? '-',
                        $item->siswa->nis ?? '-',
                        $item->tanggal,
                        ucfirst(str_replace('_', ' ', $item->status)),
                        $item->keterangan ?? '-',
                        $item->guru->nama ?? '-',
                        $item->created_at->format('d/m/Y H:i')
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            \Log::error('Error in AbsensiController@export: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }

    public function rekap(Request $request)
    {
        try {
            // Query untuk rekap
            $query = Absensi::with(['siswa', 'guru'])
                ->orderBy('tanggal', 'desc');

            if ($request->has('kelompok') && $request->kelompok != '') {
                $query->whereHas('siswa', function($q) use ($request) {
                    $q->where('kelompok', $request->kelompok);
                });
            }

            if ($request->has('bulan')) {
                $bulan = Carbon::parse($request->bulan)->format('Y-m');
                $query->whereYear('tanggal', Carbon::parse($request->bulan)->year)
                      ->whereMonth('tanggal', Carbon::parse($request->bulan)->month);
            }

            $rekap_data = $query->paginate(50);

            // Statistik rekap
            $statistik = [
                'total_absensi' => Absensi::count(),
                'hadir' => Absensi::where('status', 'hadir')->count(),
                'izin' => Absensi::where('status', 'izin')->count(),
                'sakit' => Absensi::where('status', 'sakit')->count(),
                'tidak_hadir' => Absensi::where('status', 'alpa')->count(),
            ];

            return view('admin.absensi.rekap', compact('rekap_data', 'statistik'));
        } catch (\Exception $e) {
            \Log::error('Error in AbsensiController@rekap: ' . $e->getMessage());
            return view('admin.absensi.rekap', [
                'rekap_data' => collect(),
                'statistik' => []
            ])->with('error', 'Gagal memuat data rekap');
        }
    }

    public function getGuruByKelompok(Request $request)
    {
        $kelompok = $request->kelompok;
        
        if (!$kelompok) {
            return response()->json([]);
        }
        
        $guru = Guru::where('kelompok', $kelompok)->get(['id', 'nama', 'nip']);
        
        return response()->json($guru);
    }
}
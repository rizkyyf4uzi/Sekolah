<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource (Dashboard Siswa / Pilihan Kategori).
     */
    public function index(Request $request)
    {
        Log::info('SiswaController@index called - Dashboard Siswa');
        
        try {
            // Stats untuk dashboard
            $stats = [
                'total' => Siswa::count(),
                'aktif' => Siswa::aktif()->count(),
                'lulus' => Siswa::lulus()->count(),
                'pindah' => Siswa::pindah()->count(),
                'kelompok_a' => Siswa::where('kelompok', 'A')->count(),
                'kelompok_b' => Siswa::where('kelompok', 'B')->count(),
                'laki_laki' => Siswa::where('jenis_kelamin', 'L')->count(),
                'perempuan' => Siswa::where('jenis_kelamin', 'P')->count(),
            ];

            // Data untuk grafik atau keperluan lain
            $tahunAjarans = TahunAjaran::orderBy('tahun_ajaran', 'desc')->take(5)->get();
            
            return view('admin.siswa.index', compact('stats', 'tahunAjarans'));
            
        } catch (\Exception $e) {
            Log::error('Error in SiswaController@index', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return view('admin.siswa.index')
                ->with('error', 'Terjadi kesalahan saat memuat dashboard.');
        }
    }

    /**
     * Display a listing of active students (Siswa Aktif).
     */
    public function indexAktif(Request $request)
    {
        Log::info('SiswaController@indexAktif called');
        $statusTab = 'aktif';
        return view('admin.siswa.siswa-aktif.index', compact('statusTab'));
    }

    /**
     * Show the form for creating a new active student.
     */
    public function createAktif()
    {
        $kelompok = ['A', 'B'];
        $tahunAjaran = TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();
        $tahunAjaranAktif = TahunAjaran::where('is_aktif', true)->first();
        
        return view('admin.siswa.siswa-aktif.create', compact('kelompok', 'tahunAjaran', 'tahunAjaranAktif'));
    }

    /**
     * Store a newly created active student in storage.
     */
    public function storeAktif(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $validated = $this->validateSiswa($request);
            
            // Set default academic values if missing (since they were removed from the form)
            $tahunAjaranAktif = TahunAjaran::where('is_aktif', true)->first();
            
            if (!isset($validated['tahun_ajaran_id']) && $tahunAjaranAktif) {
                $validated['tahun_ajaran_id'] = $tahunAjaranAktif->id;
                $validated['tahun_ajaran'] = $tahunAjaranAktif->tahun_ajaran;
            }
            
            if (!isset($validated['tanggal_masuk'])) {
                $validated['tanggal_masuk'] = now();
            }
            
            if (!isset($validated['kelompok'])) {
                // Default to A if not specified, or implement logic based on age if needed
                $validated['kelompok'] = 'A'; 
            }

            // Set status sebagai aktif
            $validated['status_siswa'] = 'aktif';

            // Handle foto upload
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('siswa/foto', 'public');
                $validated['foto'] = $fotoPath;
            }

            // Set default values
            $validated['punya_wali'] = $request->has('punya_wali');

            Log::info('SiswaController@storeAktif - saving data', $validated);

            $siswa = Siswa::create($validated);

            DB::commit();

            return redirect()->route('admin.siswa.siswa-aktif.show', $siswa)
                ->with('success', 'Data siswa aktif berhasil ditambahkan.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($e->validator)->withInput()->with('error', 'Validasi gagal');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in SiswaController@storeAktif', ['message' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified active student.
     */
    public function showAktif(Siswa $siswa)
    {
        // Pastikan siswa yang ditampilkan adalah aktif
        if (!$siswa->isAktif()) {
            return redirect()->route('admin.siswa.siswa-aktif.index')
                ->with('error', 'Siswa tidak ditemukan atau bukan siswa aktif.');
        }
        
        $siswa->load(['tahunAjaran', 'spmb']);
        
        return view('admin.siswa.siswa-aktif.show', compact('siswa'));
    }

    /**
     * Show the form for editing the specified active student.
     */
    public function editAktif(Siswa $siswa)
    {
        // Pastikan siswa yang diedit adalah aktif
        if (!$siswa->isAktif()) {
            return redirect()->route('admin.siswa.siswa-aktif.index')
                ->with('error', 'Siswa tidak ditemukan atau bukan siswa aktif.');
        }
        
        $kelompok = ['A', 'B'];
        $tahunAjaran = TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();
        
        return view('admin.siswa.siswa-aktif.edit', compact('siswa', 'kelompok', 'tahunAjaran'));
    }

    /**
     * Update the specified active student in storage.
     */
    public function updateAktif(Request $request, Siswa $siswa)
    {
        // Pastikan siswa yang diupdate adalah aktif
        if (!$siswa->isAktif()) {
            return redirect()->route('admin.siswa.siswa-aktif.index')
                ->with('error', 'Siswa tidak ditemukan atau bukan siswa aktif.');
        }
        
        DB::beginTransaction();
        
        try {
            $validated = $this->validateSiswa($request, $siswa->id);

            // Handle foto upload
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                    Storage::disk('public')->delete($siswa->foto);
                }
                
                $fotoPath = $request->file('foto')->store('siswa/foto', 'public');
                $validated['foto'] = $fotoPath;
            }

            // Set boolean values
            $validated['punya_wali'] = $request->has('punya_wali');

            Log::info('SiswaController@updateAktif - updating data', $validated);

            $siswa->update($validated);

            DB::commit();

            return redirect()->route('admin.siswa.siswa-aktif.show', $siswa)
                ->with('success', 'Data siswa aktif berhasil diperbarui.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($e->validator)->withInput()->with('error', 'Validasi gagal');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in SiswaController@updateAktif', ['message' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified active student from storage.
     */
    public function destroyAktif(Siswa $siswa)
    {
        // Pastikan siswa yang dihapus adalah aktif
        if (!$siswa->isAktif()) {
            return redirect()->route('admin.siswa.siswa-aktif.index')
                ->with('error', 'Siswa tidak ditemukan atau bukan siswa aktif.');
        }
        
        DB::beginTransaction();
        
        try {
            // Hapus foto jika ada
            if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                Storage::disk('public')->delete($siswa->foto);
            }

            // Hapus data siswa
            $siswa->delete();

            DB::commit();

            return redirect()->route('admin.siswa.siswa-aktif.index')
                ->with('success', 'Data siswa aktif berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in SiswaController@destroyAktif', ['message' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of graduated students (Siswa Lulus) - READ ONLY.
     */
    public function indexLulus(Request $request)
    {
        Log::info('SiswaController@indexLulus called');
        return view('admin.siswa.siswa-lulus.index');
    }

    /**
     * Display the specified graduated student - READ ONLY.
     */
    public function showLulus(Siswa $siswa)
    {
        // Pastikan siswa yang ditampilkan adalah lulus
        if (!$siswa->isLulus()) {
            return redirect()->route('admin.siswa.siswa-lulus.index')
                ->with('error', 'Siswa tidak ditemukan atau bukan siswa lulus.');
        }
        
        $siswa->load(['tahunAjaran', 'spmb']);
        
        return view('admin.siswa.siswa-lulus.show', compact('siswa'));
    }

    /**
     * Update status of a siswa (patch) - General method
     */
    public function updateStatus(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'status_siswa' => 'required|in:aktif,lulus,pindah,cuti',
            'tanggal_keluar' => 'nullable|date',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        
        try {
            // Update status berdasarkan nilai yang dipilih
            $siswa->status_siswa = $validated['status_siswa'];
            
            if (in_array($validated['status_siswa'], ['lulus', 'pindah'])) {
                $siswa->tanggal_keluar = $validated['tanggal_keluar'] ?? now();
            } else {
                $siswa->tanggal_keluar = null;
            }

            if (!empty($validated['catatan'])) {
                $siswa->catatan = $validated['catatan'];
            }

            $siswa->save();

            DB::commit();

            // Redirect based on new status
            $route = match($siswa->status_siswa) {
                'aktif' => 'admin.siswa.siswa-aktif.show',
                'lulus' => 'admin.siswa.siswa-lulus.show',
                default => 'admin.siswa.index'
            };

            return redirect()->route($route, $siswa)
                ->with('success', 'Status siswa berhasil diperbarui menjadi ' . $siswa->status_label . '.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in SiswaController@updateStatus', ['message' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Export data siswa
     */
    public function export(Request $request)
    {
        try {
            $query = Siswa::with(['tahunAjaran']);
            
            if ($request->filled('kelompok')) {
                $query->where('kelompok', $request->kelompok);
            }
            
            if ($request->filled('status')) {
                $query->byStatus($request->status);
            }
            
            if ($request->filled('tahun_ajaran_id')) {
                $query->where('tahun_ajaran_id', $request->tahun_ajaran_id);
            }
            
            $siswas = $query->orderBy('nama_lengkap')->get();
            
            $filename = 'siswa_export_' . date('Ymd_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            $callback = function() use ($siswas) {
                $file = fopen('php://output', 'w');
                
                // Header CSV
                fputcsv($file, [
                    'NIK', 'NIS', 'NISN', 'Nama Lengkap', 'Nama Panggilan',
                    'Tempat Lahir', 'Tanggal Lahir', 'Usia', 'Jenis Kelamin', 'Agama',
                    'Alamat', 'Provinsi', 'Kota/Kabupaten', 'Kecamatan', 'Kelurahan',
                    'Nama Ayah', 'Pekerjaan Ayah', 'Nama Ibu', 'Pekerjaan Ibu',
                    'No HP Orang Tua', 'Email Orang Tua', 'Kelompok', 'Tahun Ajaran',
                    'Status', 'Tanggal Masuk', 'Tanggal Keluar', 'Jalur Masuk', 'Kelas', 'Guru Kelas',
                ]);
                
                // Data
                foreach ($siswas as $siswa) {
                    fputcsv($file, [
                        $siswa->nik,
                        $siswa->nis ?? '-',
                        $siswa->nisn ?? '-',
                        $siswa->nama_lengkap,
                        $siswa->nama_panggilan ?? '-',
                        $siswa->tempat_lahir,
                        $siswa->tanggal_lahir->format('d/m/Y'),
                        $siswa->usia . ' tahun',
                        $siswa->jenis_kelamin_lengkap,
                        $siswa->agama ?? '-',
                        $siswa->alamat,
                        $siswa->provinsi ?? '-',
                        $siswa->kota_kabupaten ?? '-',
                        $siswa->kecamatan ?? '-',
                        $siswa->kelurahan ?? '-',
                        $siswa->nama_ayah,
                        $siswa->pekerjaan_ayah ?? '-',
                        $siswa->nama_ibu,
                        $siswa->pekerjaan_ibu ?? '-',
                        $siswa->no_hp_ortu,
                        $siswa->email_ortu ?? '-',
                        'Kelompok ' . $siswa->kelompok,
                        $siswa->tahunAjaran->tahun_ajaran ?? $siswa->tahun_ajaran,
                        $siswa->status_label,
                        $siswa->tanggal_masuk->format('d/m/Y'),
                        $siswa->tanggal_keluar ? $siswa->tanggal_keluar->format('d/m/Y') : '-',
                        $siswa->jalur_masuk ?? '-',
                        $siswa->kelas ?? '-',
                        $siswa->guru_kelas ?? '-',
                    ]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            Log::error('Error in SiswaController@export', ['message' => $e->getMessage()]);
            return back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }

    /**
     * Bulk update status siswa
     */
    public function bulkUpdateStatus(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:siswas,id',
            'status_siswa' => 'required|in:aktif,lulus,pindah,cuti',
            'tanggal_keluar' => 'nullable|date',
        ]);

        DB::beginTransaction();
        
        try {
            $siswas = Siswa::whereIn('id', $validated['ids'])->get();
            $count = 0;
            
            foreach ($siswas as $siswa) {
                $siswa->status_siswa = $validated['status_siswa'];
                
                if (in_array($validated['status_siswa'], ['lulus', 'pindah'])) {
                    $siswa->tanggal_keluar = $validated['tanggal_keluar'] ?? now();
                } else {
                    $siswa->tanggal_keluar = null;
                }
                
                $siswa->save();
                $count++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status ' . $count . ' siswa berhasil diperbarui.',
                'redirect_to' => $validated['status_siswa'] === 'lulus' ? 'lulus' : 'aktif'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in SiswaController@bulkUpdateStatus', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete siswa (Hapus massal)
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|string',
        ]);

        DB::beginTransaction();
        
        try {
            $ids = explode(',', $validated['ids']);
            $siswas = Siswa::whereIn('id', $ids)->get();
            $count = 0;
            
            foreach ($siswas as $siswa) {
                // Hapus foto jika ada
                if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                    Storage::disk('public')->delete($siswa->foto);
                }
                
                $siswa->delete();
                $count++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $count . ' data siswa berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in SiswaController@bulkDelete', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validation method untuk menghindari duplikasi kode
     */
    private function validateSiswa(Request $request, $id = null)
    {
        $rules = [
            // Data Calon Siswa
            'nama_lengkap' => 'required|string|max:255',
            'nama_panggilan' => 'nullable|string|max:100',
            'nik' => 'required|numeric|digits:16|unique:siswas,nik' . ($id ? ',' . $id : ''),
            'nis' => 'required|numeric|digits_between:5,15|unique:siswas,nis' . ($id ? ',' . $id : ''),
            'nisn' => 'required|numeric|digits:10|unique:siswas,nisn' . ($id ? ',' . $id : ''),
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before_or_equal:today',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|in:Islam,Kristen Protestan,Kristen Katolik,Hindu,Buddha,Konghucu,Lainnya',
            'anak_ke' => 'required|integer|min:1',
            'tinggal_bersama' => 'required|string',
            'status_tempat_tinggal' => 'required|string',
            'bahasa_sehari_hari' => 'required|string|max:100',
            'jarak_rumah_ke_sekolah' => 'nullable|integer',
            'waktu_tempuh_ke_sekolah' => 'nullable|integer',
            'berat_badan' => 'nullable|numeric',
            'tinggi_badan' => 'nullable|numeric',
            'golongan_darah' => 'nullable|in:A,B,AB,O',
            'penyakit_pernah_diderita' => 'nullable|string',
            'imunisasi' => 'nullable|string',
            
            // Alamat Domisili
            'alamat' => 'required|string',
            'provinsi' => 'required|string|max:100',
            'kota_kabupaten' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'kelurahan' => 'required|string|max:100',
            'nama_jalan' => 'required|string|max:255',

            // Alamat KK (conditional)
            'alamat_kk_sama' => 'nullable|boolean',
            'provinsi_kk' => 'required_without:alamat_kk_sama|nullable|string|max:100',
            'kota_kabupaten_kk' => 'required_without:alamat_kk_sama|nullable|string|max:100',
            'kecamatan_kk' => 'required_without:alamat_kk_sama|nullable|string|max:100',
            'kelurahan_kk' => 'required_without:alamat_kk_sama|nullable|string|max:100',
            'nama_jalan_kk' => 'required_without:alamat_kk_sama|nullable|string|max:255',
            'alamat_kk' => 'nullable|string',
            
            // Data Ayah
            'nama_ayah' => 'required|string|max:255',
            'nik_ayah' => 'required|numeric|digits:16',
            'tempat_lahir_ayah' => 'required|string|max:100',
            'tanggal_lahir_ayah' => 'required|date|before_or_equal:today',
            'pendidikan_ayah' => 'nullable|string',
            'pekerjaan_ayah' => 'nullable|string',
            'penghasilan_per_bulan_ayah' => 'nullable|string',
            'nomor_telepon_ayah' => 'required|numeric|digits_between:10,15',
            
            // Data Ibu
            'nama_ibu' => 'required|string|max:255',
            'nik_ibu' => 'required|numeric|digits:16',
            'tempat_lahir_ibu' => 'required|string|max:100',
            'tanggal_lahir_ibu' => 'required|date|before_or_equal:today',
            'pendidikan_ibu' => 'nullable|string',
            'pekerjaan_ibu' => 'nullable|string',
            'penghasilan_per_bulan_ibu' => 'nullable|string',
            'nomor_telepon_ibu' => 'required|numeric|digits_between:10,15',
            
            // Data Wali
            'punya_wali' => 'nullable|boolean',
            'nama_wali' => 'required_if:punya_wali,1|nullable|string|max:255',
            'nik_wali' => 'required_if:punya_wali,1|nullable|numeric|digits:16',
            'tempat_lahir_wali' => 'required_if:punya_wali,1|nullable|string|max:100',
            'tanggal_lahir_wali' => 'required_if:punya_wali,1|nullable|date|before_or_equal:today',
            'nomor_telepon_wali' => 'required_if:punya_wali,1|nullable|numeric|digits_between:10,15',
            'hubungan_dengan_anak' => 'required_if:punya_wali,1|nullable|string',
            'pendidikan_wali' => 'nullable|string',
            'pekerjaan_wali' => 'nullable|string',

            // Akademik & Foto
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ];

        // Filter out fields that are no longer used or redundant
        return $request->validate($rules);
    }

    /**
     * METHODS UNTUK SISWA UMUM (YANG LAMA)
     * Tetap dipertahankan untuk kompatibilitas
     */
    
    public function create()
    {
        return redirect()->route('admin.siswa.index');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.siswa.index');
    }

    public function show(Siswa $siswa)
    {
        // Redirect ke halaman yang sesuai berdasarkan status
        if ($siswa->isAktif()) {
            return redirect()->route('admin.siswa.siswa-aktif.show', $siswa);
        } elseif ($siswa->isLulus()) {
            return redirect()->route('admin.siswa.siswa-lulus.show', $siswa);
        }
        
        return view('admin.siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa)
    {
        // Redirect ke halaman yang sesuai berdasarkan status
        if ($siswa->isAktif()) {
            return redirect()->route('admin.siswa.siswa-aktif.edit', $siswa);
        } elseif ($siswa->isLulus()) {
            return redirect()->route('admin.siswa.siswa-lulus.edit', $siswa);
        }
        
        return redirect()->route('admin.siswa.index');
    }

    public function update(Request $request, Siswa $siswa)
    {
        return redirect()->route('admin.siswa.index');
    }

    public function destroy(Siswa $siswa)
    {
        return redirect()->route('admin.siswa.index');
    }

    /**
     * Display a listing of graduated students recap by academic year.
     */
    public function rekapLulus(Request $request)
    {
        Log::info('SiswaController@rekapLulus called');
        $statusTab = 'lulus';
        return view('admin.siswa.siswa-lulus.index', compact('statusTab'));
    }

    /**
     * Display list of graduated students by academic year.
     */
    public function siswaByTahunLulus(Request $request, $tahun)
    {
        Log::info('SiswaController@siswaByTahunLulus called', ['tahun' => $tahun]);
        $tahunAjaran = $tahun;
        return view('admin.siswa.siswa-lulus.by-tahun', compact('tahunAjaran'));
    }

    /**
         * Export rekap lulus to CSV
     */
    public function exportRekapLulus(Request $request)
    {
        try {
            $rekap = Siswa::lulus()
                ->select('tahun_ajaran', DB::raw('count(*) as jumlah_siswa'))
                ->groupBy('tahun_ajaran')
                ->orderBy('tahun_ajaran', 'desc')
                ->get();
            
            $filename = 'rekap_lulus_' . date('Ymd_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            $callback = function() use ($rekap) {
                $file = fopen('php://output', 'w');
                
                // Header CSV
                fputcsv($file, ['No', 'Tahun Ajaran', 'Jumlah Siswa Lulus']);
                
                // Data
                foreach ($rekap as $index => $item) {
                    fputcsv($file, [
                        $index + 1,
                        $item->tahun_ajaran,
                        $item->jumlah_siswa . ' Siswa'
                    ]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            Log::error('Error in SiswaController@exportRekapLulus', ['message' => $e->getMessage()]);
            return back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }
}
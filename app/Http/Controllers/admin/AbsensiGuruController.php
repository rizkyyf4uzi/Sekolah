<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbsensiGuru;
use App\Models\Guru;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AbsensiGuruController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = AbsensiGuru::query()
                ->select(
                    'absensi_guru.tanggal',
                    DB::raw('COUNT(CASE WHEN absensi_guru.status = "hadir" THEN 1 END) as hadir'),
                    DB::raw('COUNT(CASE WHEN absensi_guru.status = "sakit" THEN 1 END) as sakit'),
                    DB::raw('COUNT(CASE WHEN absensi_guru.status = "izin" THEN 1 END) as izin'),
                    DB::raw('COUNT(CASE WHEN absensi_guru.status = "alpa" THEN 1 END) as alpa')
                )
                ->groupBy('absensi_guru.tanggal')
                ->orderBy('absensi_guru.tanggal', 'desc');

            // filters
            if ($request->filled('bulan')) {
                $bulan = Carbon::parse($request->bulan . '-01');
                $query->whereYear('absensi_guru.tanggal', $bulan->year)
                    ->whereMonth('absensi_guru.tanggal', $bulan->month);
            }

            if ($request->filled('range')) {
                $range = $request->range;
                if ($range === 'today') {
                    $query->whereDate('absensi_guru.tanggal', now()->toDateString());
                } elseif ($range === 'yesterday') {
                    $query->whereDate('absensi_guru.tanggal', now()->subDay()->toDateString());
                } elseif ($range === 'last7') {
                    $query->whereBetween('absensi_guru.tanggal', [now()->subDays(6)->toDateString(), now()->toDateString()]);
                }
            }

            if ($request->filled('q')) {
                $q = trim($request->q);
                $query->join('gurus', 'absensi_guru.guru_id', '=', 'gurus.id')
                    ->where(function ($w) use ($q) {
                        $w->where('gurus.nama', 'like', '%' . $q . '%');

                        try {
                            $date = Carbon::parse($q)->toDateString();
                            $w->orWhereDate('absensi_guru.tanggal', $date);
                        } catch (\Exception $e) {
                            // ignore non-date search
                        }
                    });
            }

            $rows = $query->paginate(20)->withQueryString();

            return view('admin.absensi-guru.index', [
                'rows' => $rows,
                'filters' => [
                    'q' => $request->q,
                    'bulan' => $request->bulan,
                    'range' => $request->range,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error in AbsensiGuruController@index: ' . $e->getMessage());

            return view('admin.absensi-guru.index', [
                'rows' => collect(),
                'filters' => [
                    'q' => $request->q,
                    'bulan' => $request->bulan,
                    'range' => $request->range,
                ],
            ])->with('error', 'Gagal memuat data absensi guru');
        }
    }

    public function fill(Request $request)
    {
        $tanggal = $request->get('tanggal', now()->format('Y-m-d'));

        $guru = Guru::orderBy('nama')->get();

        $existing = AbsensiGuru::whereIn('guru_id', $guru->pluck('id'))
            ->whereDate('tanggal', $tanggal)
            ->get()
            ->keyBy('guru_id');

        return view('admin.absensi-guru.fill', compact('guru', 'tanggal', 'existing'));
    }

    public function storeBatch(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'statuses' => 'required|array',
            'statuses.*' => 'in:hadir,izin,sakit,alpa',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $tanggal = $request->tanggal;

            foreach ($request->statuses as $guruId => $status) {
                $keterangan = $request->keterangan[$guruId] ?? null;

                AbsensiGuru::updateOrCreate(
                    ['guru_id' => $guruId, 'tanggal' => $tanggal],
                    ['status' => $status, 'keterangan' => $keterangan]
                );
            }

            DB::commit();

            return redirect()->route('admin.absensi-guru.index')
                ->with('success', 'Absensi guru berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in AbsensiGuruController@storeBatch: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan absensi guru: ' . $e->getMessage());
        }
    }

    public function detail(Request $request)
    {
        $tanggal = $request->get('tanggal', now()->format('Y-m-d'));

        $guru = Guru::orderBy('nama')->get();

        $existing = AbsensiGuru::with('guru')
            ->whereDate('tanggal', $tanggal)
            ->get()
            ->keyBy('guru_id');

        $stats = [
            'hadir' => $existing->where('status', 'hadir')->count(),
            'sakit' => $existing->where('status', 'sakit')->count(),
            'izin' => $existing->where('status', 'izin')->count(),
            'alpa' => $existing->where('status', 'alpa')->count(),
        ];

        return view('admin.absensi-guru.detail', compact('guru', 'tanggal', 'existing', 'stats'));
    }

    public function rekap(Request $request)
    {
        $bulan = $request->get('bulan', now()->format('Y-m'));
        $bulanCarbon = Carbon::parse($bulan . '-01');

        $rekap = Guru::query()
            ->leftJoin('absensi_guru', function ($join) use ($bulanCarbon) {
                $join->on('gurus.id', '=', 'absensi_guru.guru_id')
                    ->whereYear('absensi_guru.tanggal', $bulanCarbon->year)
                    ->whereMonth('absensi_guru.tanggal', $bulanCarbon->month);
            })
            ->select(
                'gurus.id',
                'gurus.nama',
                'gurus.nip',
                DB::raw('COUNT(CASE WHEN absensi_guru.status = "hadir" THEN 1 END) as hadir'),
                DB::raw('COUNT(CASE WHEN absensi_guru.status = "sakit" THEN 1 END) as sakit'),
                DB::raw('COUNT(CASE WHEN absensi_guru.status = "izin" THEN 1 END) as izin'),
                DB::raw('COUNT(CASE WHEN absensi_guru.status = "alpa" THEN 1 END) as alpa')
            )
            ->groupBy('gurus.id', 'gurus.nama', 'gurus.nip')
            ->orderBy('gurus.nama')
            ->paginate(30)
            ->withQueryString();

        return view('admin.absensi-guru.rekap', [
            'rekap' => $rekap,
            'bulan' => $bulan,
        ]);
    }
}


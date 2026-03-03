@extends('layouts.admin')

@push('styles')
<style type="text/tailwindcss">
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
</style>
@endpush

@section('content')
<!-- Breadcrumb -->
<nav aria-label="Breadcrumb" class="flex mb-4 text-xs font-medium text-slate-400 uppercase tracking-widest">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li><a href="{{ route('admin.dashboard') }}" class="hover:text-primary">Akademik</a></li>
        <li><span class="mx-2">/</span></li>
        <li class="text-slate-600">Rekap Absensi</li>
    </ol>
</nav>

<!-- Header Section -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Rekap Absensi</h1>
        <p class="text-sm text-slate-500 mt-1 text-balance">Laporan komprehensif kehadiran siswa untuk analisis dan evaluasi.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.absensi.export', request()->query()) }}" class="flex items-center gap-2 px-6 py-3 bg-green-600 text-white rounded-2xl font-bold text-sm hover:bg-green-700 transition-all shadow-lg shadow-green-600/20">
            <span class="material-symbols-outlined text-lg">file_download</span>
            Export Excel
        </a>
    </div>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Absensi</p>
        <p class="text-3xl font-bold text-slate-900">{{ number_format($statistik['total_absensi'] ?? 0) }}</p>
        <div class="mt-2 flex items-center gap-1 text-[10px] font-bold text-primary">
            <span class="material-symbols-outlined text-xs">analytics</span>
            Data Terakumulasi
        </div>
    </div>
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Hadir</p>
        <p class="text-3xl font-bold text-green-600">{{ number_format($statistik['hadir'] ?? 0) }}</p>
        <div class="mt-2 flex items-center gap-1 text-[10px] font-bold text-green-500">
            <span class="material-symbols-outlined text-xs">check_circle</span>
            Kehadiran Positif
        </div>
    </div>
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Izin / Sakit</p>
        <p class="text-3xl font-bold text-amber-500">{{ number_format($statistik['izin'] ?? 0) }}</p>
        <div class="mt-2 flex items-center gap-1 text-[10px] font-bold text-amber-500">
            <span class="material-symbols-outlined text-xs">info</span>
            Berhalangan Hadir
        </div>
    </div>
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Alpa</p>
        <p class="text-3xl font-bold text-red-500">{{ number_format($statistik['tidak_hadir'] ?? 0) }}</p>
        <div class="mt-2 flex items-center gap-1 text-[10px] font-bold text-red-500">
            <span class="material-symbols-outlined text-xs">cancel</span>
            Tanpa Keterangan
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <h2 class="text-xl font-bold text-slate-800">Data Rekapitulasi</h2>
        
        <form action="{{ route('admin.absensi.rekap') }}" method="GET" class="flex items-center gap-3">
            <div class="relative w-48">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">group</span>
                <select name="kelompok" class="w-full pl-9 pr-4 py-2 bg-slate-50 border-none rounded-xl text-xs font-bold" onchange="this.form.submit()">
                    <option value="">Semua Kelompok</option>
                    <option value="A" {{ request('kelompok') == 'A' ? 'selected' : '' }}>Kelompok A</option>
                    <option value="B" {{ request('kelompok') == 'B' ? 'selected' : '' }}>Kelompok B</option>
                </select>
            </div>
            <div class="relative w-48">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">calendar_month</span>
                <input type="month" name="bulan" value="{{ request('bulan') }}" class="w-full pl-9 pr-4 py-2 bg-slate-50 border-none rounded-xl text-xs font-bold" onchange="this.form.submit()">
            </div>
            @if(request()->anyFilled(['kelompok', 'bulan']))
            <a href="{{ route('admin.absensi.rekap') }}" class="p-2 text-slate-400 hover:text-red-500 transition-all">
                <span class="material-symbols-outlined">restart_alt</span>
            </a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="pl-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider w-16">No</th>
                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider">Siswa</th>
                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider">Kelompok</th>
                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider text-center">Status</th>
                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider">Guru Pengajar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($rekap_data as $index => $item)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="pl-6 py-4 text-sm font-medium text-slate-400">{{ $index + 1 + (($rekap_data->currentPage() - 1) * $rekap_data->perPage()) }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-primary font-bold text-sm">
                                {{ strtoupper(substr($item->siswa?->nama ?? '?', 0, 1)) }}
                            </div>
                            <span class="text-sm font-bold text-slate-800">{{ $item->siswa?->nama ?? 'Tidak ditemukan' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider 
                            {{ ($item->siswa?->kelompok ?? '') == 'A' ? 'bg-blue-100 text-blue-700' : 
                               (($item->siswa?->kelompok ?? '') == 'B' ? 'bg-green-100 text-green-700' : 
                               'bg-slate-100 text-slate-700') }}">
                            Kelompok {{ $item->siswa?->kelompok ?? '-' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm font-medium text-slate-600">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($item->status == 'hadir')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-widest bg-green-100 text-green-700">
                                HADIR
                            </span>
                        @elseif($item->status == 'izin')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-widest bg-blue-100 text-blue-700">
                                IZIN
                            </span>
                        @elseif($item->status == 'sakit')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-widest bg-amber-100 text-amber-700">
                                SAKIT
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-widest bg-red-100 text-red-700">
                                ALPA
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm font-medium text-slate-600">{{ $item->guru->nama ?? '-' }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                        <span class="material-symbols-outlined text-4xl mb-2">inventory_2</span>
                        <p class="text-sm">Belum ada data rekap absensi tersedia.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($rekap_data->hasPages())
    <div class="px-6 py-4 border-t border-slate-50 text-xs">
        {{ $rekap_data->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
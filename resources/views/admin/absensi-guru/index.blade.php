@extends('layouts.admin')

@section('title', 'Absensi Guru')

@section('breadcrumb', 'Akademik / Absensi Guru')

@section('content')
<nav aria-label="Breadcrumb" class="flex mb-4 text-xs font-medium text-slate-400 uppercase tracking-widest">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li><span class="text-slate-600">Akademik</span></li>
        <li><span class="mx-2">/</span></li>
        <li class="text-slate-600">Absensi Guru</li>
    </ol>
</nav>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Absensi Guru</h1>
        <p class="text-sm text-slate-500 mt-1">Monitoring kehadiran guru harian dan akumulasi bulanan.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.absensi-guru.rekap', array_filter(['bulan' => $filters['bulan'] ?? null])) }}"
           class="flex items-center gap-2 px-6 py-3 bg-white text-primary border-2 border-primary/20 rounded-2xl font-bold text-sm hover:bg-primary/5 transition-all shadow-sm">
            <span class="material-symbols-outlined text-lg">assessment</span>
            Rekap Absensi
        </a>
        <a href="{{ route('admin.absensi-guru.fill') }}"
           class="flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-2xl font-bold text-sm hover:bg-primary/90 transition-all shadow-lg shadow-primary/25">
            <span class="material-symbols-outlined text-lg">fact_check</span>
            Input Absensi
        </a>
    </div>
</div>

<form method="GET" class="bg-white rounded-2xl p-6 mb-8 border border-slate-100 shadow-sm flex flex-wrap items-center gap-4">
    <div class="flex-1 min-w-[300px] relative">
        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
        <input name="q" value="{{ $filters['q'] ?? '' }}"
               class="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 text-sm transition-all"
               placeholder="Cari tanggal atau nama guru..." type="text"/>
    </div>
    <div class="w-full md:w-48">
        <input name="bulan" value="{{ $filters['bulan'] ?? '' }}"
               class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 text-sm text-slate-600 transition-all cursor-pointer"
               type="month"/>
    </div>
    <div class="w-full md:w-48">
        <select name="range" class="w-full px-4 py-3 bg-primary/10 border-2 border-primary/20 rounded-xl focus:ring-2 focus:ring-primary/20 text-sm text-primary font-bold transition-all cursor-pointer outline-none">
            <option value="today" {{ ($filters['range'] ?? '') === 'today' ? 'selected' : '' }}>Hari Ini</option>
            <option value="yesterday" {{ ($filters['range'] ?? '') === 'yesterday' ? 'selected' : '' }}>Kemarin</option>
            <option value="last7" {{ ($filters['range'] ?? '') === 'last7' ? 'selected' : '' }}>7 Hari Terakhir</option>
        </select>
    </div>
    <div class="flex gap-2">
        <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary/90 transition-all shadow-lg shadow-primary/20">
            Terapkan
        </button>
        <a href="{{ route('admin.absensi-guru.index') }}"
           class="px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-200 transition-all">
            Reset Filter
        </a>
    </div>
</form>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider w-16">No</th>
                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider text-center">Total Hadir</th>
                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider text-center">Total Sakit</th>
                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider text-center">Total Izin</th>
                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider text-center">Total Alpa</th>
                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($rows as $row)
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="px-6 py-4 text-sm font-medium text-slate-400">
                        {{ $loop->iteration + ($rows->currentPage()-1)*$rows->perPage() }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-slate-800">
                                {{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('l, d M Y') }}
                            </span>
                            <span class="text-[10px] text-slate-400 uppercase font-bold tracking-tight">Presensi Harian</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">
                            {{ (int) $row->hadir }} Guru
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-sm font-medium text-slate-600">{{ (int) $row->sakit }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-sm font-medium text-slate-600">{{ (int) $row->izin }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-sm font-bold text-red-500">{{ (int) $row->alpa }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center">
                            <a href="{{ route('admin.absensi-guru.detail', ['tanggal' => \Carbon\Carbon::parse($row->tanggal)->format('Y-m-d')]) }}"
                               class="p-2 bg-primary/10 text-primary rounded-lg hover:bg-primary hover:text-white transition-all"
                               title="Show Detail">
                                <span class="material-symbols-outlined text-lg">visibility</span>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <span class="material-symbols-outlined text-4xl text-slate-300">event_busy</span>
                            <p class="text-sm font-bold text-slate-500">Belum ada data absensi guru</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-slate-50 flex items-center justify-between gap-4 flex-col sm:flex-row">
        <p class="text-xs text-slate-400 font-medium">
            @if($rows instanceof \Illuminate\Pagination\LengthAwarePaginator && $rows->total() > 0)
                Showing <span class="text-slate-900">{{ $rows->firstItem() }}</span> of <span class="text-slate-900">{{ $rows->total() }}</span> records
            @else
                Showing <span class="text-slate-900">0</span> records
            @endif
        </p>
        @if($rows instanceof \Illuminate\Pagination\LengthAwarePaginator && $rows->hasPages())
            <div class="w-full sm:w-auto">
                {{ $rows->links() }}
            </div>
        @endif
    </div>
</div>
@endsection


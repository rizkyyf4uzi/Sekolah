@extends('layouts.admin')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Absensi Siswa</h1>
        <p class="text-sm text-slate-500 mt-1 text-balance">Kelola dan pantau kehadiran siswa harian berdasarkan kelompok belajar.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.absensi.rekap') }}" class="flex items-center gap-2 px-6 py-3 bg-white text-primary border border-primary/20 rounded-2xl font-bold text-sm hover:bg-primary/5 transition-all shadow-sm">
            <span class="material-symbols-outlined text-lg">description</span>
            Rekap Absensi
        </a>
        <a href="{{ route('admin.absensi.fill') }}" class="flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-2xl font-bold text-sm hover:bg-primary/90 transition-all shadow-lg shadow-primary/25">
            <span class="material-symbols-outlined text-lg">add</span>
            Input Absensi
        </a>
    </div>
</div>

<form action="{{ route('admin.absensi.index') }}" method="GET" id="filterForm">
    <div class="flex bg-white p-1 rounded-2xl shadow-sm border border-slate-100 mb-6 max-w-fit">
        <button type="button" onclick="setKelompok('')" class="px-8 py-3 {{ !request('kelompok') ? 'bg-primary text-white shadow-md' : 'text-slate-500 hover:bg-slate-50' }} rounded-xl text-sm font-bold transition-all">
            Semua
        </button>
        <button type="button" onclick="setKelompok('A')" class="px-8 py-3 {{ request('kelompok') == 'A' ? 'bg-primary text-white shadow-md' : 'text-slate-500 hover:bg-slate-50' }} rounded-xl text-sm font-bold transition-all">
            Kelompok A
        </button>
        <button type="button" onclick="setKelompok('B')" class="px-8 py-3 {{ request('kelompok') == 'B' ? 'bg-primary text-white shadow-md' : 'text-slate-500 hover:bg-slate-50' }} rounded-xl text-sm font-bold transition-all">
            Kelompok B
        </button>
        <input type="hidden" name="kelompok" id="kelompokInput" value="{{ request('kelompok', '') }}">
    </div>

    <div class="bg-white rounded-2xl p-6 mb-8 border border-slate-100 shadow-sm flex flex-wrap items-center gap-4">
        <div class="flex-1 min-w-[300px] relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
            <input type="date" name="tanggal" value="{{ request('tanggal') }}" onchange="this.form.submit()" class="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 text-sm transition-all">
        </div>
        <a href="{{ route('admin.absensi.index') }}" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-200 transition-all text-center">
            Reset Filter
        </a>
    </div>
</form>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="pl-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider w-16">No</th>
                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider">Kelompok</th>
                    <th class="px-4 py-4 text-[11px] font-black text-green-600 uppercase tracking-wider text-center">Hadir</th>
                    <th class="px-4 py-4 text-[11px] font-black text-amber-600 uppercase tracking-wider text-center">Sakit</th>
                    <th class="px-4 py-4 text-[11px] font-black text-blue-600 uppercase tracking-wider text-center">Izin</th>
                    <th class="px-4 py-4 text-[11px] font-black text-red-600 uppercase tracking-wider text-center">Alpa</th>
                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($absensi as $index => $item)
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="pl-6 py-4 text-sm font-medium text-slate-400">
                        {{ ($absensi->currentPage() - 1) * $absensi->perPage() + $index + 1 }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d M Y') }}</span>
                            <span class="text-[10px] text-slate-400 uppercase tracking-wide">Smt {{ $item->semester ?? 'Ganjil' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                            Kelompok {{ $item->kelompok }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="text-sm font-bold text-slate-800">{{ $item->hadir }}</span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="text-sm font-bold text-amber-600">{{ $item->sakit }}</span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="text-sm font-bold text-blue-600">{{ $item->izin }}</span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="text-sm font-bold text-red-500">{{ $item->alpa }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center">
                            <a href="{{ route('admin.absensi.detail', ['tanggal' => $item->tanggal, 'kelompok' => $item->kelompok]) }}" class="p-2 bg-slate-50 hover:bg-primary/10 text-slate-400 hover:text-primary rounded-lg transition-all" title="Show Detail">
                                <span class="material-symbols-outlined text-lg">visibility</span>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <span class="material-symbols-outlined text-slate-200 text-6xl mb-4">event_busy</span>
                            <p class="text-slate-500 font-medium">Belum ada data absensi untuk kriteria ini.</p>
                            <a href="{{ route('admin.absensi.fill') }}" class="mt-4 text-primary font-bold hover:underline">Input Absensi Sekarang</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($absensi->hasPages())
    <div class="px-6 py-4 border-t border-slate-50 flex items-center justify-between">
        <p class="text-xs text-slate-400 font-medium">
            Menampilkan <span class="text-slate-900">{{ $absensi->firstItem() }}</span> sampai <span class="text-slate-900">{{ $absensi->lastItem() }}</span> dari <span class="text-slate-900">{{ $absensi->total() }}</span> rekaman absensi
        </p>
        <div class="flex gap-2">
            @if(!$absensi->onFirstPage())
            <a href="{{ $absensi->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:bg-slate-50 transition-colors">
                <span class="material-symbols-outlined text-lg">chevron_left</span>
            </a>
            @endif
            
            @foreach($absensi->getUrlRange(max(1, $absensi->currentPage() - 2), min($absensi->lastPage(), $absensi->currentPage() + 2)) as $page => $url)
                @if($page == $absensi->currentPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-primary text-white text-xs font-bold">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-50">{{ $page }}</a>
                @endif
            @endforeach

            @if($absensi->hasMorePages())
            <a href="{{ $absensi->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:bg-slate-50 transition-colors">
                <span class="material-symbols-outlined text-lg">chevron_right</span>
            </a>
            @endif
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    function setKelompok(val) {
        document.getElementById('kelompokInput').value = val;
        document.getElementById('filterForm').submit();
    }
</script>
@endpush
@endsection
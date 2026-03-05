@extends('layouts.admin')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 md:gap-6 mb-6 md:mb-8">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">Absensi Siswa</h1>
        <p class="text-sm text-slate-500 mt-1 text-balance">Kelola dan pantau kehadiran siswa harian berdasarkan kelompok belajar.</p>
    </div>
    <div class="flex flex-wrap items-center gap-3">
        <a href="{{ route('admin.absensi.rekap') }}" class="flex items-center justify-center gap-2 px-6 py-3 bg-white text-primary border border-primary/20 rounded-2xl font-bold text-sm hover:bg-primary/5 transition-all shadow-sm flex-1 md:flex-none">
            <span class="material-symbols-outlined text-lg">description</span>
            Rekap Absensi
        </a>
        <a href="{{ route('admin.absensi.fill') }}" class="flex items-center justify-center gap-2 px-6 py-3 bg-primary text-white rounded-2xl font-bold text-sm hover:bg-primary/90 transition-all shadow-lg shadow-primary/25 flex-1 md:flex-none">
            <span class="material-symbols-outlined text-lg">add</span>
            Input Absensi
        </a>
    </div>
</div>

<form action="{{ route('admin.absensi.index') }}" method="GET" id="filterForm">
    <!-- Filter Kelompok -->
    <div class="flex flex-wrap bg-white p-1.5 rounded-2xl shadow-sm border border-slate-100 mb-6 w-full md:max-w-fit gap-1">
        <button type="button" onclick="setKelompok('')" class="flex-1 md:flex-none px-6 md:px-8 py-3 {{ !request('kelompok') ? 'bg-primary text-white shadow-md' : 'text-slate-500 hover:bg-slate-50' }} rounded-xl text-sm font-bold transition-all whitespace-nowrap">
            Semua
        </button>
        <button type="button" onclick="setKelompok('A')" class="flex-1 md:flex-none px-6 md:px-8 py-3 {{ request('kelompok') == 'A' ? 'bg-primary text-white shadow-md' : 'text-slate-500 hover:bg-slate-50' }} rounded-xl text-sm font-bold transition-all whitespace-nowrap">
            Kelp. A
        </button>
        <button type="button" onclick="setKelompok('B')" class="flex-1 md:flex-none px-6 md:px-8 py-3 {{ request('kelompok') == 'B' ? 'bg-primary text-white shadow-md' : 'text-slate-500 hover:bg-slate-50' }} rounded-xl text-sm font-bold transition-all whitespace-nowrap">
            Kelp. B
        </button>
        <input type="hidden" name="kelompok" id="kelompokInput" value="{{ request('kelompok', '') }}">
    </div>

    <!-- Filter Taggal -->
    <div class="bg-white rounded-2xl p-4 md:p-6 mb-8 border border-slate-100 shadow-sm flex flex-col sm:flex-row items-center gap-4">
        <div class="w-full sm:flex-1 relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
            <input type="date" name="tanggal" value="{{ request('tanggal') }}" onchange="this.form.submit()" class="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 text-sm transition-all">
        </div>
        <a href="{{ route('admin.absensi.index') }}" class="w-full sm:w-auto px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-200 transition-all text-center whitespace-nowrap">
            Reset Filter
        </a>
    </div>
</form>

<!-- Desktop Table View -->
<div class="hidden md:block bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
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
</div>

<!-- Mobile Card View -->
<div class="md:hidden flex flex-col gap-4">
    @forelse($absensi as $index => $item)
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden">
        <!-- Decoration indicating Kelompok -->
        <div class="absolute top-0 right-0 w-24 h-24 {{ $item->kelompok == 'A' ? 'bg-blue-50/50' : 'bg-green-50/50' }} rounded-bl-full -z-10"></div>
        
        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="text-slate-800 font-bold text-base mb-1">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d M Y') }}</p>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider {{ $item->kelompok == 'A' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                        Kelompok {{ $item->kelompok }}
                    </span>
                    <span class="text-[10px] text-slate-400 uppercase tracking-wide font-bold">SMT {{ $item->semester ?? 'Ganjil' }}</span>
                </div>
            </div>
            <a href="{{ route('admin.absensi.detail', ['tanggal' => $item->tanggal, 'kelompok' => $item->kelompok]) }}" class="w-10 h-10 bg-primary/5 text-primary hover:bg-primary hover:text-white rounded-xl flex items-center justify-center transition-all shadow-sm">
                <span class="material-symbols-outlined text-xl">visibility</span>
            </a>
        </div>

        <div class="grid grid-cols-4 gap-2 border-t border-slate-100 pt-4">
            <div class="flex flex-col items-center justify-center p-2 rounded-xl bg-slate-50/50">
                <span class="text-lg font-black text-slate-800">{{ $item->hadir }}</span>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider mt-1">Hadir</span>
            </div>
            <div class="flex flex-col items-center justify-center p-2 rounded-xl bg-amber-50/30">
                <span class="text-lg font-black text-amber-600">{{ $item->sakit }}</span>
                <span class="text-[9px] font-black text-amber-500 uppercase tracking-wider mt-1">Sakit</span>
            </div>
            <div class="flex flex-col items-center justify-center p-2 rounded-xl bg-blue-50/30">
                <span class="text-lg font-black text-blue-600">{{ $item->izin }}</span>
                <span class="text-[9px] font-black text-blue-500 uppercase tracking-wider mt-1">Izin</span>
            </div>
            <div class="flex flex-col items-center justify-center p-2 rounded-xl bg-red-50/30">
                <span class="text-lg font-black text-red-500">{{ $item->alpa }}</span>
                <span class="text-[9px] font-black text-red-400 uppercase tracking-wider mt-1">Alpa</span>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-3xl p-8 border border-slate-100 text-center shadow-sm">
        <span class="material-symbols-outlined text-slate-200 text-6xl mb-4">event_busy</span>
        <p class="text-slate-500 font-medium">Belum ada data absensi untuk kriteria ini.</p>
        <a href="{{ route('admin.absensi.fill') }}" class="mt-4 text-primary font-bold hover:underline inline-block">Input Absensi Sekarang</a>
    </div>
    @endforelse
</div>

<!-- Pagination (Both Desktop & Mobile) -->
@if($absensi->hasPages())
<div class="bg-white md:bg-transparent rounded-2xl md:rounded-none md:border-none border border-slate-100 shadow-sm md:shadow-none p-4 md:px-6 md:py-4 mt-4 md:mt-0 flex flex-col md:flex-row items-center justify-between gap-4">
    <p class="text-xs text-slate-400 font-medium text-center md:text-left">
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

@push('scripts')
<script>
    function setKelompok(val) {
        document.getElementById('kelompokInput').value = val;
        document.getElementById('filterForm').submit();
    }
</script>
@endpush
@endsection
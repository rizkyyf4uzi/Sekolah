@extends('layouts.admin')

@section('content')
<nav aria-label="Breadcrumb" class="flex mb-4 text-xs font-medium text-slate-400 uppercase tracking-widest">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li><a class="hover:text-primary" href="{{ route('admin.dashboard') }}">Akademik</a></li>
        <li><span class="mx-2">/</span></li>
        <li><a class="hover:text-primary" href="{{ route('admin.absensi.index') }}">Absensi Siswa</a></li>
        <li><span class="mx-2">/</span></li>
        <li class="text-slate-600">Detail</li>
    </ol>
</nav>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
    <div class="flex items-start gap-4">
        <a href="{{ route('admin.absensi.index') }}" class="mt-1 p-2 bg-white text-slate-400 hover:text-primary border border-slate-100 rounded-xl transition-all shadow-sm group">
            <span class="material-symbols-outlined text-xl">arrow_back</span>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Detail Absensi Siswa</h1>
            <div class="flex items-center gap-3 mt-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold">
                    <span class="material-symbols-outlined text-sm">calendar_today</span>
                    {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d M Y') }}
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                    <span class="material-symbols-outlined text-sm">group</span>
                    Kelompok {{ $kelompok }}
                </span>
                @if($siswa->first() && $siswa->first()->tahunAjaran)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold">
                    <span class="material-symbols-outlined text-sm">history_edu</span>
                    Semester {{ $siswa->first()->tahunAjaran->semester ?? 'Ganjil' }}
                </span>
                @endif
            </div>
        </div>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.absensi.fill', ['tanggal' => $tanggal, 'kelompok' => $kelompok]) }}" class="flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-2xl font-bold text-sm hover:bg-primary/90 transition-all shadow-lg shadow-primary/25">
            <span class="material-symbols-outlined text-lg">edit</span>
            Edit Absensi
        </a>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-5">
        <div class="w-14 h-14 rounded-2xl bg-green-50 flex items-center justify-center text-green-600 flex-shrink-0">
            <span class="material-symbols-outlined text-3xl font-medium">check_circle</span>
        </div>
        <div>
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Hadir</p>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['hadir'] ?? 0 }}</p>
            <p class="text-[10px] text-green-600 font-medium mt-0.5">{{ $total > 0 ? round((($stats['hadir'] ?? 0) / $total) * 100, 1) : 0 }}% dari total</p>
        </div>
    </div>
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-5">
        <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 flex-shrink-0">
            <span class="material-symbols-outlined text-3xl font-medium">medical_services</span>
        </div>
        <div>
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Sakit</p>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['sakit'] ?? 0 }}</p>
            <p class="text-[10px] text-amber-600 font-medium mt-0.5">{{ $total > 0 ? round((($stats['sakit'] ?? 0) / $total) * 100, 1) : 0 }}% dari total</p>
        </div>
    </div>
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-5">
        <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
            <span class="material-symbols-outlined text-3xl font-medium">info</span>
        </div>
        <div>
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Izin</p>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['izin'] ?? 0 }}</p>
            <p class="text-[10px] text-slate-400 font-medium mt-0.5">{{ $total > 0 ? round((($stats['izin'] ?? 0) / $total) * 100, 1) : 0 }}% dari total</p>
        </div>
    </div>
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-5">
        <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center text-red-600 flex-shrink-0">
            <span class="material-symbols-outlined text-3xl font-medium">cancel</span>
        </div>
        <div>
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Alpa</p>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['tidak_hadir'] ?? 0 }}</p>
            <p class="text-[10px] text-slate-400 font-medium mt-0.5">{{ $total > 0 ? round((($stats['tidak_hadir'] ?? 0) / $total) * 100, 1) : 0 }}% dari total</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="p-6 border-b border-slate-50 flex items-center justify-between">
        <h3 class="text-lg font-bold text-slate-800">Daftar Kehadiran Siswa</h3>
        <div class="flex items-center gap-3">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                <input class="pl-9 pr-4 py-2 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 text-xs w-64 transition-all" placeholder="Cari nama siswa..." type="text" id="searchInput" onkeyup="filterTable()">
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="siswaTable">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="pl-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider w-16">No</th>
                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider">NIS</th>
                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider">Nama Lengkap</th>
                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider text-center">Status Kehadiran</th>
                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($siswa as $index => $s)
                @php 
                    $existingItem = optional($existing->get($s->id));
                    $status = $existingItem->status ?? 'belum_diisi';
                    $keterangan = $existingItem->keterangan ?? '-';
                @endphp
                <tr class="hover:bg-slate-50/30 transition-colors siswa-row">
                    <td class="pl-6 py-4 text-sm font-medium text-slate-400">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 text-sm font-bold text-slate-600 tracking-tight">{{ $s->nis ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm font-bold text-slate-800 name-col">{{ $s->nama_lengkap ?? $s->nama }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($status == 'hadir')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-green-100 text-green-700">Hadir</span>
                        @elseif($status == 'sakit')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-700">Sakit</span>
                        @elseif($status == 'izin')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-100 text-blue-700">Izin</span>
                        @elseif($status == 'tidak_hadir' || $status == 'alpa')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-red-100 text-red-700">Alpa</span>
                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-500">Belum Diisi</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm {{ $keterangan !== '-' ? 'text-slate-600 italic' : 'text-slate-400' }}">{{ $keterangan }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-slate-50 flex items-center justify-between">
        <p class="text-xs text-slate-400 font-medium">Total <span class="text-slate-900" id="rowCount">{{ $siswa->count() }}</span> siswa</p>
    </div>
</div>

@push('scripts')
<script>
    function filterTable() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        tr = document.querySelectorAll(".siswa-row");
        let visibleCount = 0;

        for (i = 0; i < tr.length; i++) {
            td = tr[i].querySelector(".name-col");
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                    visibleCount++;
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
        document.getElementById('rowCount').textContent = visibleCount;
    }
</script>
@endpush
@endsection

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

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 md:gap-6 mb-6 md:mb-8">
    <div class="flex items-start gap-4">
        <a href="{{ route('admin.absensi.index') }}" class="mt-1 p-2 bg-white text-slate-400 hover:text-primary border border-slate-100 rounded-xl transition-all shadow-sm group shrink-0">
            <span class="material-symbols-outlined text-xl">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">Detail Absensi Siswa</h1>
            <div class="flex flex-wrap items-center gap-2 md:gap-3 mt-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-primary/10 text-primary text-[10px] md:text-xs font-bold whitespace-nowrap">
                    <span class="material-symbols-outlined text-[12px] md:text-sm">calendar_today</span>
                    {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d M Y') }}
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-[10px] md:text-xs font-bold whitespace-nowrap">
                    <span class="material-symbols-outlined text-[12px] md:text-sm">group</span>
                    Kelompok {{ $kelompok }}
                </span>
                @if($siswa->first() && $siswa->first()->tahunAjaran)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-[10px] md:text-xs font-bold whitespace-nowrap">
                    <span class="material-symbols-outlined text-[12px] md:text-sm">history_edu</span>
                    Semester {{ $siswa->first()->tahunAjaran->semester ?? 'Ganjil' }}
                </span>
                @endif
            </div>
        </div>
    </div>
    <div class="flex items-center gap-3 w-full md:w-auto mt-2 md:mt-0">
        <a href="{{ route('admin.absensi.fill', ['tanggal' => $tanggal, 'kelompok' => $kelompok]) }}" class="flex items-center justify-center gap-2 px-6 py-3 bg-primary text-white rounded-2xl font-bold text-sm hover:bg-primary/90 transition-all shadow-lg shadow-primary/25 w-full md:w-auto relative group">
            <div class="absolute inset-0 bg-white/20 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <span class="material-symbols-outlined text-lg">edit</span>
            <span>Edit Absensi</span>
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
    <div class="p-4 md:p-6 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <h3 class="text-lg font-bold text-slate-800">Daftar Kehadiran Siswa</h3>
        <div class="flex items-center gap-3 w-full md:w-auto">
            <div class="relative w-full md:w-auto">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                <input class="w-full md:w-64 pl-9 pr-4 py-2 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 text-xs transition-all" placeholder="Cari nama siswa..." type="text" id="searchInput" onkeyup="filterTable()">
            </div>
        </div>
    </div>
    
    <!-- Table View (Desktop) -->
    <div class="hidden md:block overflow-x-auto">
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
    
    <!-- Mobile Card View -->
    <div class="md:hidden flex flex-col divide-y divide-slate-50">
        @foreach($siswa as $index => $s)
        @php 
            $existingItem = optional($existing->get($s->id));
            $status = $existingItem->status ?? 'belum_diisi';
            $keterangan = $existingItem->keterangan ?? '-';
            
            // Generate Initial colors
            $colors = [
                ['bg' => 'bg-lavender', 'text' => 'text-primary'],
                ['bg' => 'bg-purple-100', 'text' => 'text-primary'],
                ['bg' => 'bg-amber-100', 'text' => 'text-amber-700'],
                ['bg' => 'bg-green-100', 'text' => 'text-green-700'],
                ['bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
                ['bg' => 'bg-rose-100', 'text' => 'text-rose-700'],
            ];
            $colorClass = $colors[$index % count($colors)];
            $nameParts = explode(' ', $s->nama_lengkap ?? $s->nama);
            $initials = '';
            foreach(array_slice($nameParts, 0, 2) as $part) {
                if(!empty($part)) $initials .= strtoupper(substr($part, 0, 1));
            }
            if(empty($initials)) $initials = '?';
        @endphp
        <div class="p-4 siswa-card transition-colors hover:bg-slate-50/30">
            <div class="flex items-start justify-between gap-3 mb-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-full {{ $colorClass['bg'] }} {{ $colorClass['text'] }} flex items-center justify-center font-bold text-sm shrink-0">{{ $initials }}</div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-slate-800 truncate name-col-mobile">{{ $s->nama_lengkap ?? $s->nama }}</p>
                        <p class="text-xs text-slate-400 font-medium">{{ $s->nis ?? 'No NIS' }}</p>
                    </div>
                </div>
                <div>
                    @if($status == 'hadir')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-green-100 text-green-700">Hadir</span>
                    @elseif($status == 'sakit')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-700">Sakit</span>
                    @elseif($status == 'izin')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-blue-100 text-blue-700">Izin</span>
                    @elseif($status == 'tidak_hadir' || $status == 'alpa')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-red-100 text-red-700">Alpa</span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-500">Kosong</span>
                    @endif
                </div>
            </div>
            @if($keterangan !== '-')
            <div class="bg-slate-50/50 rounded-xl p-3 border border-slate-100/50 ml-13">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Catatan</p>
                <p class="text-xs text-slate-600 italic leading-relaxed">{{ $keterangan }}</p>
            </div>
            @endif
        </div>
        @endforeach
    </div>
    
    <div class="px-6 py-4 border-t border-slate-50 flex items-center justify-between">
        <p class="text-xs text-slate-400 font-medium">Total <span class="text-slate-900" id="rowCount">{{ $siswa->count() }}</span> siswa</p>
    </div>
</div>

@push('scripts')
<script>
    function filterTable() {
        var input, filter, tr, cards, td, cardName, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        let visibleCount = 0;

        // Filter Table Rows
        tr = document.querySelectorAll(".siswa-row");
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

        // Filter Mobile Cards
        cards = document.querySelectorAll(".siswa-card");
        let cardVisibleCount = 0;
        for (i = 0; i < cards.length; i++) {
            cardName = cards[i].querySelector(".name-col-mobile");
            if (cardName) {
                txtValue = cardName.textContent || cardName.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    cards[i].style.display = "";
                    cardVisibleCount++;
                } else {
                    cards[i].style.display = "none";
                }
            }
        }

        // Use desktop row count since cards are duplicates
        document.getElementById('rowCount').textContent = visibleCount;
    }
</script>
@endpush
@endsection

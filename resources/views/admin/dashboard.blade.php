@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
{{-- Welcome Hero --}}
<div class="relative bg-gradient-to-r from-primary to-purple-800 rounded-2xl p-6 sm:p-8 mb-6 sm:mb-8 overflow-hidden min-h-[200px] sm:min-h-[220px] flex items-center shadow-xl shadow-primary/20">
    <div class="relative z-10 max-w-lg">
        <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">Halo, {{ Auth::user()->name }}!</h1>
        <p class="text-white/80 mb-4 sm:mb-6 leading-relaxed text-sm sm:text-base">
            Selamat datang di dashboard. Akses ringkasan data, kelola informasi, dan pantau operasional secara real-time..
        </p>
        <a href="#stats-section" 
           onclick="event.preventDefault(); document.getElementById('stats-section').scrollIntoView({ behavior: 'smooth', block: 'start' });"
           class="inline-flex items-center px-5 py-2.5 sm:px-6 sm:py-3 bg-white text-primary rounded-xl font-bold text-sm hover:bg-lavender transition-all shadow-lg shadow-black/10">
            Jelajahi
        </a>
    </div>
    <div class="absolute right-0 top-0 bottom-0 w-1/3 flex items-center justify-center opacity-20 pointer-events-none">
        <span class="material-symbols-outlined text-[120px] sm:text-[160px] text-white">analytics</span>
    </div>
</div>

<div class="grid grid-cols-12 gap-6 sm:gap-8">
    {{-- Left Column: Stats + Recent Activity --}}
    <div class="col-span-12 lg:col-span-8 space-y-6 sm:space-y-8">
        {{-- 4 Stat Cards --}}
        <div id="stats-section" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 scroll-mt-24">
            <a href="{{ route('admin.siswa.siswa-aktif.index') }}" class="bg-white dark:bg-slate-800 p-5 sm:p-6 rounded-2xl shadow-sm hover:shadow-md transition-all border border-slate-100 dark:border-slate-700">
                <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 text-primary flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined">group</span>
                </div>
                <h3 class="text-slate-400 dark:text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Total Siswa</h3>
                <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['total_siswa'] ?? 0 }}</p>
                <p class="text-[10px] text-green-500 font-bold mt-2 flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs">trending_up</span>
                    Aktif: {{ $stats['siswa_aktif'] ?? 0 }}
                </p>
            </a>

            <a href="{{ route('admin.guru.index') }}" class="bg-white dark:bg-slate-800 p-5 sm:p-6 rounded-2xl shadow-sm hover:shadow-md transition-all border border-slate-100 dark:border-slate-700">
                <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 text-primary flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined">person_pin_circle</span>
                </div>
                <h3 class="text-slate-400 dark:text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Total Guru</h3>
                <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['total_guru'] ?? 0 }}</p>
                <p class="text-[10px] text-slate-400 font-bold mt-2">Pengajar aktif</p>
            </a>

            <a href="{{ route('admin.absensi.index') }}" class="bg-white dark:bg-slate-800 p-5 sm:p-6 rounded-2xl shadow-sm hover:shadow-md transition-all border border-slate-100 dark:border-slate-700">
                <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 text-primary flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined">how_to_reg</span>
                </div>
                <h3 class="text-slate-400 dark:text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Kehadiran</h3>
                <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['persen_absen'] ?? 0 }}%</p>
                <p class="text-[10px] text-slate-400 font-bold mt-2">Hari ini: {{ $stats['absensi_hari_ini'] ?? 0 }} absen</p>
            </a>

            <a href="{{ route('admin.spmb.index') }}" class="bg-white dark:bg-slate-800 p-5 sm:p-6 rounded-2xl shadow-sm hover:shadow-md transition-all border border-slate-100 dark:border-slate-700">
                <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 text-primary flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined">verified</span>
                </div>
                <h3 class="text-slate-400 dark:text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">PPDB</h3>
                <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['ppdb_total'] ?? 0 }}</p>
                <p class="text-[10px] text-green-500 font-bold mt-2 flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs">trending_up</span>
                    {{ $stats['pendaftaran_baru'] ?? 0 }} menunggu
                </p>
            </a>
        </div>

        {{-- Recent Activity --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm p-6 sm:p-8 border border-slate-100 dark:border-slate-700">
            <div class="flex items-center justify-between mb-6 sm:mb-8">
                <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">Aktivitas Terbaru</h2>
                <a href="{{ route('admin.spmb.index') }}" class="text-primary text-xs font-bold hover:underline">Lihat Semua</a>
            </div>
            <div class="space-y-6 sm:space-y-8">
                @forelse($recent_pendaftaran ?? [] as $item)
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-2xl {{ $item->status_pendaftaran === 'Diterima' ? 'bg-blue-100 dark:bg-blue-900/30' : 'bg-lavender dark:bg-purple-900/30' }} flex-shrink-0 flex items-center justify-center">
                        @if($item->status_pendaftaran === 'Diterima')
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-xl">verified</span>
                        @else
                        <span class="material-symbols-outlined text-primary text-xl">person_add</span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-slate-700 dark:text-slate-300 leading-snug">
                            <span class="font-bold">{{ $item->nama_lengkap_anak ?? 'Calon' }}</span>
                            {{ $item->status_pendaftaran === 'Diterima' ? ' telah diverifikasi.' : ' mengajukan pendaftaran baru.' }}
                        </p>
                        <p class="text-[10px] text-slate-400 mt-1 uppercase font-bold tracking-wider">{{ $item->created_at->diffForHumans() }}</p>
                    </div>
                    @if($item->status_pendaftaran === 'Menunggu Verifikasi')
                    <span class="text-[10px] px-2 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 rounded-lg font-bold uppercase flex-shrink-0">Pending</span>
                    @elseif($item->status_pendaftaran === 'Diterima')
                    <span class="text-[10px] px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-lg font-bold uppercase flex-shrink-0">Verified</span>
                    @else
                    <span class="text-[10px] px-2 py-1 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-lg font-bold uppercase flex-shrink-0">{{ $item->status_pendaftaran }}</span>
                    @endif
                </div>
                @empty
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-2xl bg-slate-100 dark:bg-slate-700 flex-shrink-0 flex items-center justify-center">
                        <span class="material-symbols-outlined text-slate-400 text-xl">inbox</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-slate-500 dark:text-slate-400">Belum ada aktivitas pendaftaran terbaru.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Right Column: Calendar + Profile --}}
    <div class="col-span-12 lg:col-span-4 space-y-6 sm:space-y-8">
        {{-- Calendar --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm p-5 sm:p-6 border border-slate-100 dark:border-slate-700">
            <div class="flex items-center justify-between mb-5 sm:mb-6">
                <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">{{ now()->translatedFormat('F Y') }}</h2>
                <div class="flex gap-2">
                    <button type="button" class="w-8 h-8 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 flex items-center justify-center text-slate-400" aria-label="Bulan sebelumnya">
                        <span class="material-symbols-outlined text-lg">chevron_left</span>
                    </button>
                    <button type="button" class="w-8 h-8 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 flex items-center justify-center text-slate-400" aria-label="Bulan berikutnya">
                        <span class="material-symbols-outlined text-lg">chevron_right</span>
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-7 gap-1 text-center mb-4">
                @foreach(['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day)
                <div class="text-[10px] font-black text-slate-400 uppercase">{{ $day }}</div>
                @endforeach
            </div>
            <div class="grid grid-cols-7 gap-1">
                @php
                    $calStart = \Carbon\Carbon::now()->startOfMonth()->startOfWeek(\Carbon\Carbon::SUNDAY);
                    $today = \Carbon\Carbon::now();
                @endphp
                @for($i = 0; $i < 42; $i++)
                @php
                    $cell = $calStart->copy()->addDays($i);
                    $isCurrentMonth = $cell->month === $today->month;
                    $isToday = $cell->isSameDay($today);
                @endphp
                <div class="aspect-square flex items-center justify-center text-xs {{ $isCurrentMonth ? 'text-slate-700 dark:text-slate-300' : 'text-slate-300 dark:text-slate-600' }} {{ $isToday ? 'bg-primary text-white rounded-xl shadow-lg shadow-primary/30 font-bold' : '' }}">
                    {{ $cell->day }}
                </div>
                @endfor
            </div>
        </div>

        {{-- Profile Card --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm p-5 sm:p-6 border border-slate-100 dark:border-slate-700 flex flex-col items-center text-center">
            <div class="relative mb-4">
                @auth
                    @if(Auth::user()->foto ?? null)
                    <img alt="Profile" class="w-20 h-20 rounded-2xl object-cover ring-4 ring-lavender dark:ring-primary/50" src="{{ asset('storage/' . Auth::user()->foto) }}"/>
                    @else
                    <div class="w-20 h-20 rounded-2xl bg-primary flex items-center justify-center text-2xl font-bold text-white ring-4 ring-lavender dark:ring-primary/50">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    @endif
                @else
                <div class="w-20 h-20 rounded-2xl bg-slate-400 flex items-center justify-center text-2xl font-bold text-white ring-4 ring-lavender">?</div>
                @endauth
                <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 border-2 border-white dark:border-slate-800 rounded-full"></div>
            </div>
            <h3 class="font-bold text-slate-800 dark:text-slate-100">@auth {{ Auth::user()->name }} @else Guest @endauth</h3>
            <p class="text-xs text-slate-400 mb-5 sm:mb-6">
                @auth
                    @switch(Auth::user()->role)
                        @case('admin')
                        @case('super_admin')
                            Super Admin
                            @break
                        @case('kepala_sekolah')
                            Kepala Sekolah
                            @break
                        @case('operator')
                            Operator
                            @break
                        @case('guru')
                            Guru
                            @break
                        @default
                            {{ ucfirst(Auth::user()->role) }}
                    @endswitch
                @endauth
            </p>
            <div class="w-full flex justify-between px-4 mb-5 sm:mb-6">
                <div>
                    <p class="text-lg font-bold text-primary">{{ $stats['pendaftaran_baru'] ?? 0 }}</p>
                    <p class="text-[10px] text-slate-400 uppercase font-black">Pending</p>
                </div>
                <div class="h-8 w-px bg-slate-100 dark:bg-slate-700"></div>
                <div>
                    <p class="text-lg font-bold text-primary">{{ $stats['total_siswa'] ?? 0 }}</p>
                    <p class="text-[10px] text-slate-400 uppercase font-black">Siswa</p>
                </div>
                <div class="h-8 w-px bg-slate-100 dark:bg-slate-700"></div>
                <div>
                    <p class="text-lg font-bold text-primary">{{ $stats['total_guru'] ?? 0 }}</p>
                    <p class="text-[10px] text-slate-400 uppercase font-black">Guru</p>
                </div>
            </div>
            <a href="{{ route('admin.profile.index') }}" class="w-full py-3 bg-lavender dark:bg-primary/20 text-primary font-bold text-sm rounded-xl hover:bg-primary hover:text-white transition-all">
                Lihat Profil
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-hover { transition: all 0.3s ease; }
    .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
    
    /* Smooth scroll behavior */
    html {
        scroll-behavior: smooth;
    }
    
    /* Offset for fixed header saat scroll ke anchor */
    .scroll-mt-24 {
        scroll-margin-top: 6rem;
    }
</style>
@endpush
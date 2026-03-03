@extends('layouts.admin')

@push('styles')
<style type="text/tailwindcss">
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
</style>
@endpush

@section('content')
<nav aria-label="Breadcrumb" class="flex mb-4 text-xs font-medium text-slate-400 uppercase tracking-widest">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li><a class="hover:text-primary" href="{{ route('admin.dashboard') }}">Akademik</a></li>
        <li><span class="mx-2">/</span></li>
        <li><a class="hover:text-primary" href="{{ route('admin.absensi.index') }}">Absensi Siswa</a></li>
        <li><span class="mx-2">/</span></li>
        <li class="text-slate-600">Input Absensi</li>
    </ol>
</nav>

<!-- Configuration Card (Select Date & Kelompok) -->
<div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm mb-8">
    <form method="GET" action="{{ route('admin.absensi.fill') }}" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
        <div class="space-y-2">
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih Tanggal</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">calendar_today</span>
                <input type="date" name="tanggal" value="{{ $tanggal }}" 
                       class="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 text-sm transition-all cursor-pointer"
                       onchange="this.form.submit()">
            </div>
        </div>
        
        <div class="space-y-2">
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih Kelompok</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">group</span>
                <select name="kelompok" 
                        class="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 text-sm appearance-none transition-all cursor-pointer"
                        onchange="this.form.submit()">
                    <option value="">- Pilih Kelompok -</option>
                    <option value="A" {{ $kelompok == 'A' ? 'selected' : '' }}>Kelompok A</option>
                    <option value="B" {{ $kelompok == 'B' ? 'selected' : '' }}>Kelompok B</option>
                </select>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="flex-1 bg-primary text-white px-6 py-3 rounded-2xl font-bold text-sm hover:bg-primary/90 transition-all shadow-lg shadow-primary/25">
                Tampilkan Siswa
            </button>
        </div>
    </form>
</div>

@if($kelompok && $siswa->isNotEmpty())
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Input Absensi - Kelompok {{ $kelompok }}</h1>
        <p class="text-sm text-slate-500 mt-1">Silakan isi kehadiran siswa untuk hari ini.</p>
    </div>
    <div class="flex items-center gap-4">
        <div class="relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-primary">calendar_today</span>
            <input class="pl-12 pr-4 py-3 bg-white border-none rounded-2xl focus:ring-2 focus:ring-primary/20 text-sm font-bold text-primary shadow-sm transition-all outline-none cursor-pointer text-center" style="width: 170px;" type="text" value="{{ \Carbon\Carbon::parse($tanggal)->format('Y-m-d') }}" readonly disabled />
        </div>
        @if(isset($guru) && $guru)
            <div class="hidden md:flex flex-col text-right">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Wali Kelas</span>
                <span class="text-xs font-bold text-slate-800">{{ $guru->nama }}</span>
            </div>
        @endif
    </div>
</div>

<form method="POST" action="{{ route('admin.absensi.store-batch') }}" class="space-y-8" id="form-absensi">
    @csrf
    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
    <input type="hidden" name="kelompok" value="{{ $kelompok }}">
    @if(isset($guru) && $guru)
        <input type="hidden" name="guru_id" value="{{ $guru->id }}">
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-20">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider w-16">No</th>
                        <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider w-32">NIS</th>
                        <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider">Nama Lengkap</th>
                        <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider text-center">Status Kehadiran</th>
                        <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @php
                        // Color variations for avatars
                        $colors = [
                            ['bg' => 'bg-lavender', 'text' => 'text-primary'],
                            ['bg' => 'bg-purple-100', 'text' => 'text-primary'],
                            ['bg' => 'bg-amber-100', 'text' => 'text-amber-700'],
                            ['bg' => 'bg-green-100', 'text' => 'text-green-700'],
                            ['bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
                            ['bg' => 'bg-rose-100', 'text' => 'text-rose-700'],
                        ];
                    @endphp
                    @foreach($siswa as $index => $s)
                    @php 
                        $existingStatus = optional($existing->get($s->id))->status ?? 'hadir'; 
                        $keterangan = optional($existing->get($s->id))->keterangan ?? '';
                        $colorClass = $colors[$index % count($colors)];
                        
                        // Get initials
                        $nameParts = explode(' ', $s->nama_lengkap ?? $s->nama);
                        $initials = '';
                        foreach(array_slice($nameParts, 0, 2) as $part) {
                            if(!empty($part)) $initials .= strtoupper(substr($part, 0, 1));
                        }
                        if(empty($initials)) $initials = '?';
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-5 text-sm font-medium text-slate-400">{{ $index + 1 }}</td>
                        <td class="px-6 py-5 text-sm font-bold text-slate-600">{{ $s->nis ?? '-' }}</td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full {{ $colorClass['bg'] }} {{ $colorClass['text'] }} flex items-center justify-center font-bold text-xs">{{ $initials }}</div>
                                <span class="text-sm font-bold text-slate-800">{{ $s->nama_lengkap ?? $s->nama }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center justify-center">
                                <select name="statuses[{{ $s->id }}]" class="w-36 px-3 py-2 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 text-sm font-semibold cursor-pointer transition-all appearance-none text-center">
                                    <option value="hadir" {{ $existingStatus == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="sakit" {{ $existingStatus == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                    <option value="izin" {{ $existingStatus == 'izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="alpa" {{ ($existingStatus == 'alpa' || str_contains($existingStatus, 'tidak_hadir')) ? 'selected' : '' }}>Alpa</option>
                                </select>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <input class="w-full px-4 py-2 bg-slate-50 border-none rounded-lg focus:ring-2 focus:ring-primary/20 text-xs transition-all" name="keterangan[{{ $s->id }}]" placeholder="Catatan..." type="text" value="{{ $keterangan }}"/>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Fixed Bottom Bar -->
    <div class="fixed bottom-0 right-0 left-0 lg:left-72 bg-white/80 backdrop-blur-md border-t border-slate-100 p-4 z-[40] shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] transition-all duration-300">
        <div class="max-w-7xl mx-auto flex items-center justify-end gap-4 px-4">
            <a href="{{ route('admin.absensi.index') }}" class="px-8 py-3 bg-slate-100 text-slate-600 rounded-2xl font-bold text-sm hover:bg-slate-200 transition-all hidden sm:inline-block">
                Batal
            </a>
            <button type="button" onclick="document.getElementById('form-absensi').submit();" class="px-10 py-3 bg-primary text-white rounded-2xl font-bold text-sm hover:bg-primary/90 transition-all shadow-lg shadow-primary/25 flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">save</span>
                Simpan Absensi
            </button>
        </div>
    </div>
</form>

@elseif($kelompok)
<div class="bg-white rounded-3xl p-12 border border-slate-100 text-center shadow-sm">
    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
        <span class="material-symbols-outlined text-4xl text-slate-300">person_search</span>
    </div>
    <h3 class="text-xl font-bold text-slate-800 mb-2">Tidak Ada Siswa</h3>
    <p class="text-slate-400 text-sm max-w-sm mx-auto mb-8">Belum ada siswa yang terdaftar di Kelompok {{ $kelompok }} untuk saat ini.</p>
    <a href="{{ route('admin.siswa-aktif.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary/90 transition-all shadow-lg shadow-primary/25">
        <span class="material-symbols-outlined text-lg">person_add</span>
        Tambah Siswa
    </a>
</div>
@else
<div class="bg-primary/5 border border-primary/10 rounded-3xl p-12 text-center">
    <div class="w-20 h-20 bg-primary/20 text-primary rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl shadow-primary/10">
        <span class="material-symbols-outlined text-4xl">touch_app</span>
    </div>
    <h3 class="text-xl font-bold text-primary mb-2">Pilih Kelompok</h3>
    <p class="text-slate-500 text-sm max-w-xs mx-auto">Silakan pilih kelompok belajar di atas untuk memulai pencatatan absensi.</p>
</div>
@endif

@endsection
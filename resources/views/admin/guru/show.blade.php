@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-background-light dark:bg-background-dark p-8 pb-8">
    <div class="max-w-7xl mx-auto">
        <!-- Breadcrumb -->
        <nav aria-label="Breadcrumb" class="flex mb-6">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <span class="text-slate-400 text-sm font-medium">Master Data</span>
                </li>
                <li>
                    <div class="flex items-center">
                        <span class="material-symbols-outlined text-slate-400 text-sm mx-1">chevron_right</span>
                        <span class="text-slate-400 text-sm font-medium">Data Guru</span>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <span class="material-symbols-outlined text-slate-400 text-sm mx-1">chevron_right</span>
                        <span class="text-slate-800 text-sm font-bold">Detail Profil</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div class="flex items-center gap-6">
                <div class="relative">
                    @if($guru->foto)
                        <img alt="Teacher Profile" class="w-24 h-24 rounded-3xl object-cover shadow-lg border-4 border-white" src="{{ asset('storage/' . $guru->foto) }}">
                    @else
                        <div class="w-24 h-24 rounded-3xl bg-primary/10 text-primary flex items-center justify-center border-4 border-white shadow-lg">
                            <span class="material-symbols-outlined text-4xl">person</span>
                        </div>
                    @endif
                    <div class="absolute -bottom-2 -right-2 bg-green-500 w-6 h-6 rounded-full border-4 border-background-light"></div>
                </div>
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-3xl font-bold text-slate-900">{{ $guru->nama }}</h1>
                        <span class="px-3 py-1 bg-primary/10 text-primary text-[10px] font-bold rounded-full uppercase tracking-widest">Aktif</span>
                    </div>
                    <p class="text-slate-500 font-medium mt-1">NIP: {{ $guru->nip ?? '-' }}</p>
                    <div class="flex items-center gap-4 mt-2">
                        <div class="flex items-center gap-1.5 text-sm text-slate-600">
                            <span class="material-symbols-outlined text-primary text-lg">workspace_premium</span>
                            <span>{{ $guru->jabatan_formatted }}{{ $guru->kelompok ? ' - Kelompok ' . $guru->kelompok_formatted : '' }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-sm text-slate-600">
                            <span class="material-symbols-outlined text-primary text-lg">mail</span>
                            <span>{{ $guru->email }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.guru.index') }}" class="flex items-center gap-2 px-6 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all shadow-sm">
                    <span class="material-symbols-outlined text-lg">arrow_back</span>
                    Kembali ke Daftar
                </a>
                <a href="{{ route('admin.guru.edit', $guru->id) }}" class="flex items-center gap-2 px-6 py-2.5 bg-primary text-white rounded-xl font-bold text-sm hover:opacity-90 transition-all shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined text-lg">edit</span>
                    Edit Data
                </a>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Data Pribadi -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100">
                    <div class="flex items-center gap-3 mb-8 border-b border-slate-50 pb-4">
                        <div class="w-10 h-10 bg-primary/10 text-primary rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined">person</span>
                        </div>
                        <h2 class="text-lg font-bold text-slate-800">Data Pribadi</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nama Lengkap</p>
                            <p class="text-slate-800 font-semibold">{{ $guru->nama }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">NIP</p>
                            <p class="text-slate-800 font-semibold font-mono">{{ $guru->nip ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tempat Lahir</p>
                            <p class="text-slate-800 font-semibold">{{ $guru->tempat_lahir ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tanggal Lahir</p>
                            <p class="text-slate-800 font-semibold">{{ $guru->tanggal_lahir_formatted }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Jenis Kelamin</p>
                            <p class="text-slate-800 font-semibold">{{ $guru->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Agama</p>
                            <p class="text-slate-800 font-semibold">{{ $guru->agama ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">No HP</p>
                            <p class="text-slate-800 font-semibold">{{ $guru->no_hp }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Email</p>
                            <p class="text-slate-800 font-semibold">{{ $guru->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Alamat -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-primary/10 text-primary rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined">home</span>
                        </div>
                        <h2 class="text-lg font-bold text-slate-800">Alamat</h2>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Alamat Lengkap</p>
                        <p class="text-slate-700 leading-relaxed">{{ $guru->alamat }}</p>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-8">
                <!-- Kepegawaian -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100">
                    <div class="flex items-center gap-3 mb-8 border-b border-slate-50 pb-4">
                        <div class="w-10 h-10 bg-primary/10 text-primary rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined">work</span>
                        </div>
                        <h2 class="text-lg font-bold text-slate-800">Kepegawaian</h2>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Jabatan</p>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-primary"></span>
                                <p class="text-slate-800 font-semibold">{{ $guru->jabatan_formatted }}</p>
                            </div>
                        </div>
                        @if($guru->kelompok)
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Kelompok</p>
                            <p class="text-slate-800 font-semibold">Kelompok {{ $guru->kelompok_formatted }}</p>
                        </div>
                        @endif
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pendidikan Terakhir</p>
                            <p class="text-slate-800 font-semibold">{{ $guru->pendidikan_terakhir ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

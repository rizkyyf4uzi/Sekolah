@extends('layouts.admin')

@section('title', 'Detail Siswa Lulus')
@section('breadcrumb', 'Siswa Lulus')

@section('content')
<div class="min-h-screen bg-background-light dark:bg-background-dark p-3 sm:p-4 md:p-6">
    <div class="max-w-full mx-auto">
        <!-- Breadcrumb -->
        <nav aria-label="Breadcrumb" class="flex mb-4 text-xs font-medium text-slate-400 uppercase tracking-widest">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li><a href="{{ route('admin.siswa.siswa-aktif.index') }}" class="hover:text-primary transition-colors">Master Data</a></li>
                <li><span class="mx-2">/</span></li>
                <li><a href="{{ route('admin.siswa.siswa-lulus.index') }}" class="hover:text-primary transition-colors">Siswa Lulus</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-slate-600 dark:text-slate-300">Detail Siswa</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Detail Siswa Lulus</h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">Informasi lengkap siswa yang telah lulus.</p>
            </div>
            <a href="{{ route('admin.siswa.siswa-lulus.index') }}" 
               class="flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl sm:rounded-2xl font-bold text-xs sm:text-sm hover:bg-slate-200 dark:hover:bg-slate-600 transition-all whitespace-nowrap">
                <span class="material-symbols-outlined text-base sm:text-lg">arrow_back</span>
                Kembali
            </a>
        </div>

        <!-- Content -->
        <div class="bg-white dark:bg-slate-800 rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-4 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4">Data Pribadi</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-slate-500">Nama Lengkap</p>
                            <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $siswa->nama_lengkap }}</p>
                        </div>
                        
                        <div>
                            <p class="text-xs text-slate-500">NIS / NISN</p>
                            <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $siswa->nis ?? '-' }} / {{ $siswa->nisn ?? '-' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-xs text-slate-500">Tempat, Tanggal Lahir</p>
                            <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $siswa->tempat_lahir }}, {{ $siswa->tanggal_lahir->format('d F Y') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-xs text-slate-500">Jenis Kelamin</p>
                            <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-xs text-slate-500">Kelompok</p>
                            <p class="text-sm font-bold text-slate-800 dark:text-white">Kelompok {{ $siswa->kelompok }}</p>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4">Data Kelulusan</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-slate-500">Tahun Ajaran</p>
                            <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $siswa->tahun_ajaran }}</p>
                        </div>
                        
                        <div>
                            <p class="text-xs text-slate-500">Tanggal Lulus</p>
                            <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $siswa->tanggal_keluar ? $siswa->tanggal_keluar->format('d F Y') : '-' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-xs text-slate-500">Status</p>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                Lulus
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
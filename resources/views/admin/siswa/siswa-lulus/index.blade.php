@extends('layouts.admin')

@push('meta')
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
@endpush

@section('content')
<div class="min-h-screen bg-background-light dark:bg-background-dark p-3 sm:p-4 md:p-6">
    <div class="max-w-full mx-auto">
        <!-- Breadcrumb -->
        <!-- Breadcrumb -->
        <nav aria-label="Breadcrumb" class="flex mb-4 text-xs font-medium text-slate-400 uppercase tracking-widest">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li><a href="#" class="hover:text-primary transition-colors">Master Data</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-slate-600 dark:text-slate-300">Data Siswa</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Data Siswa</h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">Ringkasan data siswa yang telah menyelesaikan masa pendidikan berdasarkan tahun ajaran.</p>
            </div>
            <a href="{{ route('admin.siswa.siswa-lulus.export') }}" 
               class="flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-3 bg-primary text-white rounded-xl sm:rounded-2xl font-bold text-xs sm:text-sm hover:bg-primary/90 transition-all shadow-lg shadow-primary/25 whitespace-nowrap">
                <span class="material-symbols-outlined text-base sm:text-lg">download</span>
                Export Rekapitulasi
            </a>
        </div>

        <!-- Tab navigasi -->
        <div class="flex items-center gap-2 mb-6 border-b border-slate-200 dark:border-slate-700">
            <a href="{{ route('admin.siswa.siswa-aktif.index') }}" 
               class="px-4 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm font-bold text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 border-b-2 border-transparent hover:border-slate-300 transition-all whitespace-nowrap">
                Siswa Aktif
            </a>
            <a href="{{ route('admin.siswa.siswa-lulus.index') }}" 
               class="px-4 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm font-bold text-primary border-b-2 border-primary transition-all whitespace-nowrap">
                Siswa Lulus
            </a>
        </div>

        <!-- Livewire Component -->
        <livewire:admin.siswa.siswa-lulus-rekap />
    </div>
</div>
@endsection

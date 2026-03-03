@extends('layouts.admin')

@push('meta')
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
@endpush

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
                <li class="text-slate-600 dark:text-slate-300">Tahun {{ $tahunAjaran }}</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white tracking-tight">
                    Siswa Lulus Tahun {{ $tahunAjaran }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Daftar siswa yang lulus pada tahun ajaran {{ $tahunAjaran }}
                </p>
            </div>
            <a href="{{ route('admin.siswa.siswa-lulus.index') }}" 
               class="flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl sm:rounded-2xl font-bold text-xs sm:text-sm hover:bg-slate-200 dark:hover:bg-slate-600 transition-all whitespace-nowrap">
                <span class="material-symbols-outlined text-base sm:text-lg">arrow_back</span>
                Kembali ke Rekap
            </a>
        </div>

        <!-- Livewire Component -->
        <livewire:admin.siswa.siswa-lulus-by-tahun :tahun="$tahunAjaran" />
    </div>
</div>
@endsection

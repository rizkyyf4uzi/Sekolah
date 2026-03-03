@extends('layouts.admin')

@push('meta')
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
@endpush

@section('content')
<div class="max-w-7xl mx-auto space-y-8 pb-20">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <nav aria-label="Breadcrumb" class="flex mb-4 text-xs font-medium text-slate-400 uppercase tracking-widest">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li><a href="#" class="hover:text-primary transition-colors">Master Data</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-slate-600 dark:text-slate-300">Data Siswa</li>
            </ol>
        </nav>
        
        <div class="flex items-center gap-3">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Data Siswa</h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola dan atur informasi data siswa dengan kategori status akademik.</p>
            </div>
            <a href="{{ route('admin.guru.create') }}" 
               class="px-6 py-4 rounded-2xl bg-primary text-white font-black text-xs uppercase tracking-widest shadow-xl shadow-primary/20 hover:scale-105 transition-all active:scale-95 flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">add</span>
                Tambah Guru
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div id="stats-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @include('admin.guru.partials.stats', ['gurus' => $gurus])
    </div>

    <!-- Main Content Card -->
    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden border-b-4 border-b-primary/10">
        <!-- Search & Filter Bar -->
        <div class="p-8 border-b border-slate-100 dark:border-slate-700/50 space-y-6">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <!-- Search -->
                <div class="flex-1 relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-slate-400 group-focus-within:text-primary transition-colors text-xl">search</span>
                    </div>
                    <input type="text" 
                           id="search-input" 
                           placeholder="Cari nama guru atau NIP..." 
                           class="w-full pl-14 pr-14 py-4 bg-slate-50 dark:bg-slate-900 border-transparent rounded-[1.25rem] focus:bg-white dark:focus:bg-slate-800 focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all text-sm font-bold text-slate-600 dark:text-slate-300 placeholder:text-slate-400"
                           value="{{ request('search', '') }}">
                    
                    <div id="search-loading" class="absolute inset-y-0 right-5 flex items-center hidden">
                        <div class="w-5 h-5 border-2 border-primary/20 border-t-primary rounded-full animate-spin"></div>
                    </div>
                    
                    <button id="search-clear" 
                            class="absolute inset-y-0 right-4 flex items-center px-2 text-slate-300 hover:text-rose-500 transition-colors hidden">
                        <span class="material-symbols-outlined text-xl">close</span>
                    </button>
                </div>

                <!-- Filters -->
                <div class="flex flex-wrap items-center gap-3">
                    <div class="relative min-w-[160px]">
                        <select id="filter-jabatan" class="w-full pl-4 pr-10 py-4 bg-slate-50 dark:bg-slate-900 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all text-xs font-black uppercase tracking-widest appearance-none cursor-pointer">
                            <option value="">Semua Jabatan</option>
                            <option value="guru" {{ request('jabatan') == 'guru' ? 'selected' : '' }}>Guru</option>
                            <option value="staff" {{ request('jabatan') == 'staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                            <span class="material-symbols-outlined text-lg font-bold">expand_more</span>
                        </div>
                    </div>

                    <div id="kelompok-filter-container" class="relative min-w-[160px] {{ request('jabatan') == 'guru' ? '' : 'hidden' }}">
                        <select id="filter-kelompok" class="w-full pl-4 pr-10 py-4 bg-slate-50 dark:bg-slate-900 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all text-xs font-black uppercase tracking-widest appearance-none cursor-pointer">
                            <option value="">Semua Kelompok</option>
                            <option value="A" {{ request('kelompok') == 'A' ? 'selected' : '' }}>Kelompok A</option>
                            <option value="B" {{ request('kelompok') == 'B' ? 'selected' : '' }}>Kelompok B</option>
                        </select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                            <span class="material-symbols-outlined text-lg font-bold">expand_more</span>
                        </div>
                    </div>

                    <button id="reset-filters" 
                            class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 text-slate-400 hover:text-primary hover:bg-white dark:hover:bg-slate-800 border-none transition-all flex items-center gap-2 group shadow-sm"
                            title="Reset Filter">
                        <span class="material-symbols-outlined text-lg group-active:rotate-180 transition-transform duration-500">restart_alt</span>
                        <span class="text-[10px] font-black uppercase tracking-widest">Reset</span>
                    </button>
                </div>
            </div>

            <!-- Search Info -->
            <div id="search-info" class="hidden">
                <div class="inline-flex items-center gap-3 px-4 py-2 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400">
                    <span class="material-symbols-outlined text-lg">info</span>
                    <span class="text-xs font-bold tracking-tight">
                        Menampilkan hasil untuk: <span id="search-term" class="underline underline-offset-4 decoration-primary decoration-2 font-black"></span>
                    </span>
                    <button id="clear-search-info" class="ml-2 w-6 h-6 rounded-lg bg-white dark:bg-slate-800 flex items-center justify-center shadow-sm hover:text-rose-500 transition-colors">
                        <span class="material-symbols-outlined text-sm font-black">close</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div id="guru-table-container" class="relative min-h-[400px]">
            @include('admin.guru.partials.table', ['gurus' => $gurus, 'search' => $search ?? ''])
        </div>

        <!-- Pagination -->
        <div id="pagination-container" class="p-8 border-t border-slate-100 dark:border-slate-700/50 bg-slate-50/30 dark:bg-slate-900/20">
            @if(isset($gurus) && $gurus->hasPages())
                {{ $gurus->onEachSide(1)->links() }}
            @endif
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loading-overlay" class="fixed inset-0 bg-slate-900/20 backdrop-blur-[2px] z-[60] flex items-center justify-center transition-all duration-300 opacity-0 pointer-events-none hidden">
    <div class="bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] shadow-2xl flex flex-col items-center gap-4">
        <div class="relative">
            <div class="w-16 h-16 border-4 border-primary/10 border-t-primary rounded-full animate-spin"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary animate-pulse">groups</span>
            </div>
        </div>
        <span class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Memproses Data...</span>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Custom Scrollbar for Table */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    
    /* Animation for table rows */
    #guru-table-container tbody tr {
        animation: slideInUp 0.3s ease-out forwards;
        opacity: 0;
    }
    
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @for ($i = 1; $i <= 20; $i++)
        #guru-table-container tbody tr:nth-child({{ $i }}) {
            animation-delay: {{ $i * 0.05 }}s;
        }
    @endfor

    /* Custom Pagination Styling */
    .pagination {
        @apply flex justify-center gap-2;
    }
    .pagination li span, .pagination li a {
        @apply w-10 h-10 rounded-xl flex items-center justify-center font-black text-xs transition-all shadow-sm;
    }
    .pagination li.active span {
        @apply bg-primary text-white shadow-lg shadow-primary/20 scale-110;
    }
    .pagination li a {
        @apply bg-white dark:bg-slate-800 text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-primary;
    }
    .pagination li:first-child a, .pagination li:last-child a {
        @apply w-auto px-4;
    }
</style>
@endpush

@push('scripts')
@vite(['resources/js/guru-search.js'])

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loadingOverlay = document.getElementById('loading-overlay');
        
        function showLoading() {
            loadingOverlay.classList.remove('hidden', 'opacity-0', 'pointer-events-none');
        }
        
        function hideLoading() {
            loadingOverlay.classList.add('opacity-0', 'pointer-events-none');
            setTimeout(() => loadingOverlay.classList.add('hidden'), 300);
        }

        // Jabatan filter logic
        const jabatanFilter = document.getElementById('filter-jabatan');
        const kelompokFilterContainer = document.getElementById('kelompok-filter-container');
        
        if (jabatanFilter && kelompokFilterContainer) {
            jabatanFilter.addEventListener('change', function() {
                if (this.value === 'guru') {
                    kelompokFilterContainer.classList.remove('hidden');
                } else {
                    kelompokFilterContainer.classList.add('hidden');
                    document.getElementById('filter-kelompok').value = '';
                }
            });
        }

        // Reset button
        const resetBtn = document.getElementById('reset-filters');
        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                showLoading();
                window.location.href = '{{ route("admin.guru.index") }}';
            });
        }
    });
</script>
@endpush
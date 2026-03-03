@extends('layouts.admin')

@section('content')
{{-- Header --}}
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Data Guru</h1>
        <p class="text-sm text-slate-500 mt-1">Manajemen data tenaga pengajar dan staf sekolah.</p>
    </div>
    <a href="{{ route('admin.guru.create') }}"
       class="flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-2xl font-bold text-sm hover:bg-primary/90 transition-all shadow-lg shadow-primary/25">
        <span class="material-symbols-outlined text-lg">person_add</span>
        Tambah Guru
    </a>
</div>

{{-- Search & Filter --}}
<div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm mb-6">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
        {{-- Search --}}
        <div class="md:col-span-5 relative group">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
            <input type="text"
                   id="search-input"
                   placeholder="Cari nama atau NIP..."
                   class="w-full pl-12 pr-10 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 text-sm transition-all"
                   value="{{ request('search', '') }}">
            <div id="search-loading" class="absolute inset-y-0 right-5 flex items-center hidden">
                <div class="w-4 h-4 border-2 border-primary/20 border-t-primary rounded-full animate-spin"></div>
            </div>
            <button id="search-clear" class="absolute inset-y-0 right-4 flex items-center px-1 text-slate-300 hover:text-rose-500 transition-colors hidden">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
        </div>

        {{-- Filter Jabatan --}}
        <div class="md:col-span-3 relative">
            <select id="filter-jabatan" class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 text-sm text-slate-600 transition-all cursor-pointer appearance-none">
                <option value="">Semua Jabatan</option>
                <option value="guru"  {{ request('jabatan') == 'guru'  ? 'selected' : '' }}>Guru</option>
                <option value="staff" {{ request('jabatan') == 'staff' ? 'selected' : '' }}>Staff</option>
            </select>
        </div>

        {{-- Filter Kelompok --}}
        <div id="kelompok-filter-container" class="md:col-span-3 relative {{ request('jabatan') == 'guru' ? '' : 'hidden' }}">
            <select id="filter-kelompok" class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 text-sm text-slate-600 transition-all cursor-pointer appearance-none">
                <option value="">Semua Kelompok</option>
                <option value="A" {{ request('kelompok') == 'A' ? 'selected' : '' }}>Kelompok A</option>
                <option value="B" {{ request('kelompok') == 'B' ? 'selected' : '' }}>Kelompok B</option>
            </select>
        </div>

        {{-- Reset --}}
        <div class="md:col-span-1">
            <button id="reset-filters"
                    class="w-full h-full flex items-center justify-center bg-lavender text-primary rounded-xl hover:bg-primary hover:text-white transition-all"
                    title="Reset Filter">
                <span class="material-symbols-outlined">filter_list</span>
            </button>
        </div>
    </div>

    {{-- Search info --}}
    <div id="search-info" class="hidden mt-4">
        <div class="inline-flex items-center gap-3 px-4 py-2 rounded-xl bg-indigo-50 text-indigo-600">
            <span class="material-symbols-outlined text-lg">info</span>
            <span class="text-xs font-bold">
                Hasil pencarian: <span id="search-term" class="underline underline-offset-4 decoration-primary decoration-2 font-black"></span>
            </span>
            <button id="clear-search-info" class="ml-2 w-6 h-6 rounded-lg bg-white flex items-center justify-center shadow-sm hover:text-rose-500 transition-colors">
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div id="guru-table-container" class="overflow-x-auto">
        @include('admin.guru.partials.table', ['gurus' => $gurus, 'search' => $search ?? ''])
    </div>

    {{-- Pagination --}}
    <div id="pagination-container" class="px-6 py-4 border-t border-slate-50">
        @if(isset($gurus) && $gurus->hasPages())
            {{ $gurus->onEachSide(1)->links() }}
        @endif
    </div>
</div>

{{-- Loading Overlay --}}
<div id="loading-overlay" class="fixed inset-0 bg-slate-900/20 backdrop-blur-[2px] z-[60] flex items-center justify-center transition-all duration-300 opacity-0 pointer-events-none hidden">
    <div class="bg-white p-8 rounded-3xl shadow-2xl flex flex-col items-center gap-4">
        <div class="relative">
            <div class="w-16 h-16 border-4 border-primary/10 border-t-primary rounded-full animate-spin"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary animate-pulse">groups</span>
            </div>
        </div>
        <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Memproses Data...</span>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Animation for table rows */
    #guru-table-container tbody tr {
        animation: slideInUp 0.3s ease-out forwards;
        opacity: 0;
    }
    @keyframes slideInUp {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @for ($i = 1; $i <= 20; $i++)
        #guru-table-container tbody tr:nth-child({{ $i }}) {
            animation-delay: {{ $i * 0.04 }}s;
        }
    @endfor

    /* Pagination */
    .pagination { @apply flex justify-center gap-2; }
    .pagination li span,
    .pagination li a { @apply w-10 h-10 rounded-xl flex items-center justify-center font-black text-xs transition-all shadow-sm; }
    .pagination li.active span { @apply bg-primary text-white shadow-lg shadow-primary/20 scale-110; }
    .pagination li a { @apply bg-white text-slate-400 hover:bg-slate-50 hover:text-primary; }
    .pagination li:first-child a,
    .pagination li:last-child a { @apply w-auto px-4; }
</style>
@endpush

@push('scripts')
@vite(['resources/js/guru-search.js'])

<script>
document.addEventListener('DOMContentLoaded', function () {
    const loadingOverlay = document.getElementById('loading-overlay');

    function showLoading() {
        loadingOverlay.classList.remove('hidden', 'opacity-0', 'pointer-events-none');
    }
    function hideLoading() {
        loadingOverlay.classList.add('opacity-0', 'pointer-events-none');
        setTimeout(() => loadingOverlay.classList.add('hidden'), 300);
    }

    // Jabatan filter → show/hide kelompok filter
    const jabatanFilter = document.getElementById('filter-jabatan');
    const kelompokFilterContainer = document.getElementById('kelompok-filter-container');
    if (jabatanFilter && kelompokFilterContainer) {
        jabatanFilter.addEventListener('change', function () {
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
        resetBtn.addEventListener('click', function () {
            showLoading();
            window.location.href = '{{ route("admin.guru.index") }}';
        });
    }
});
</script>
@endpush
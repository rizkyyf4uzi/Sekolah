@extends('layouts.admin')

@section('title', 'Tahun Ajaran')

@section('breadcrumb', 'Master Data / Tahun Ajaran')

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-4 md:p-6 rounded-2xl shadow-sm border border-slate-100">
        <div class="relative w-full md:w-96">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">search</span>
            <input class="w-full pl-12 pr-4 py-2.5 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 text-sm transition-all" placeholder="Cari tahun ajaran..." type="text"/>
        </div>
        <div class="flex gap-3 w-full md:w-auto">
            <a href="{{ route('admin.tahun-ajaran.create') }}" class="flex items-center justify-center gap-2 px-6 py-2.5 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 w-full md:w-auto">
                <span class="material-symbols-outlined text-xl">add</span>
                Tambah Tahun Ajaran
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">No</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tahun Ajaran</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Periode Tanggal</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($tahunAjaran as $ta)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-slate-600">
                            {{ $loop->iteration + ($tahunAjaran->currentPage()-1)*$tahunAjaran->perPage() }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-800 tracking-tight">{{ $ta->tahun_ajaran }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $ta->semester }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $ta->tanggal_mulai->translatedFormat('d M Y') }} - {{ $ta->tanggal_selesai->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($ta->is_aktif)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-green-100 text-green-700 uppercase tracking-wider">
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-red-100 text-red-700 uppercase tracking-wider">
                                    Tidak Aktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                @if(!$ta->is_aktif)
                                <form action="{{ route('admin.tahun-ajaran.set-active', $ta) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" 
                                            class="w-8 h-8 md:w-10 md:h-10 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all shadow-sm flex items-center justify-center border border-emerald-100"
                                            title="Aktifkan Periode Ini">
                                        <span class="material-symbols-outlined text-base md:text-lg">check_circle</span>
                                    </button>
                                </form>
                                @endif

                                <a href="{{ route('admin.tahun-ajaran.edit', $ta) }}" 
                                   class="w-8 h-8 md:w-10 md:h-10 rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm flex items-center justify-center border border-indigo-100"
                                   title="Edit Periode">
                                    <span class="material-symbols-outlined text-base md:text-lg">edit</span>
                                </a>

                                <form action="{{ route('admin.tahun-ajaran.destroy', $ta) }}" method="POST" class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                            onclick="confirmDelete(this, '{{ $ta->tahun_ajaran }} ({{ $ta->semester }})')"
                                            class="w-8 h-8 md:w-10 md:h-10 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all shadow-sm flex items-center justify-center border border-rose-100"
                                            title="Hapus Periode">
                                        <span class="material-symbols-outlined text-base md:text-lg">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <span class="material-symbols-outlined text-4xl text-slate-300">calendar_today</span>
                                <p class="text-sm font-bold text-slate-500">Belum ada data tahun ajaran</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden flex flex-col divide-y divide-slate-50">
            @forelse($tahunAjaran as $ta)
            <div class="p-4 transition-colors hover:bg-slate-50/50">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-12 h-12 rounded-xl {{ $ta->is_aktif ? 'bg-green-100 text-green-600' : 'bg-slate-100 text-slate-500' }} flex flex-col items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-xl">{{ $ta->is_aktif ? 'event_available' : 'event' }}</span>
                        </div>
                        <div class="min-w-0 flex flex-col">
                            <span class="text-base font-bold text-slate-800 truncate">{{ $ta->tahun_ajaran }}</span>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $ta->semester }}</span>
                                @if($ta->is_aktif)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded flex items-center gap-1 text-[9px] font-bold bg-green-100 text-green-700 uppercase tracking-wider">
                                        Aktif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-xs text-slate-600 bg-slate-50/50 border border-slate-100 p-2.5 rounded-xl mb-3 flex items-center gap-2 ml-[60px]">
                    <span class="material-symbols-outlined text-sm text-slate-400">calendar_month</span>
                    <span>{{ $ta->tanggal_mulai->translatedFormat('d M Y') }} - {{ $ta->tanggal_selesai->translatedFormat('d M Y') }}</span>
                </div>

                <div class="flex items-center justify-end gap-2 ml-[60px]">
                    @if(!$ta->is_aktif)
                    <form action="{{ route('admin.tahun-ajaran.set-active', $ta) }}" method="POST" class="inline flex-1">
                        @csrf
                        @method('PUT')
                        <button type="submit" 
                                class="w-full h-10 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all shadow-sm flex items-center justify-center gap-1.5 border border-emerald-100 text-xs font-bold"
                                title="Aktifkan">
                            <span class="material-symbols-outlined text-[16px]">check_circle</span>
                            <span>Set Aktif</span>
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('admin.tahun-ajaran.edit', $ta) }}" 
                       class="{{ !$ta->is_aktif ? 'w-10' : 'flex-1' }} h-10 rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm flex items-center justify-center gap-1.5 border border-indigo-100 text-xs font-bold"
                       title="Edit Periode">
                        <span class="material-symbols-outlined text-[16px]">edit</span>
                        @if($ta->is_aktif) <span>Edit</span> @endif
                    </a>

                    <form action="{{ route('admin.tahun-ajaran.destroy', $ta) }}" method="POST" class="inline delete-form {{ !$ta->is_aktif ? 'w-10' : 'flex-1' }}">
                        @csrf
                        @method('DELETE')
                        <button type="button" 
                                onclick="confirmDelete(this, '{{ $ta->tahun_ajaran }} ({{ $ta->semester }})')"
                                class="w-full h-10 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all shadow-sm flex items-center justify-center gap-1.5 border border-rose-100 text-xs font-bold"
                                title="Hapus Periode">
                            <span class="material-symbols-outlined text-[16px]">delete</span>
                            @if($ta->is_aktif) <span>Hapus</span> @endif
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="px-6 py-12 text-center">
                <div class="flex flex-col items-center gap-2">
                    <span class="material-symbols-outlined text-4xl text-slate-300">calendar_today</span>
                    <p class="text-sm font-bold text-slate-500">Belum ada data tahun ajaran</p>
                </div>
            </div>
            @endforelse
        </div>

        <div class="px-6 py-4 bg-slate-50/30 border-t border-slate-100 flex items-center justify-between gap-4 flex-col sm:flex-row">
            <p class="text-xs text-slate-500 font-medium">
                @if($tahunAjaran->total() > 0)
                    Showing {{ $tahunAjaran->firstItem() }} to {{ $tahunAjaran->lastItem() }} of {{ $tahunAjaran->total() }} results
                @else
                    Showing 0 results
                @endif
            </p>
            @if($tahunAjaran->hasPages())
                <div class="w-full sm:w-auto">
                    {{ $tahunAjaran->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(button, name) {
        Swal.fire({
            title: 'Hapus Tahun Ajaran?',
            html: `Apakah Anda yakin ingin menghapus <b>${name}</b>?<br><small class="text-rose-500 font-bold uppercase mt-2 block tracking-widest">Pastikan tidak ada data yang terkait!</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'YA, HAPUS DATA',
            cancelButtonText: 'BATALKAN',
            background: document.documentElement.classList.contains('dark') ? '#0f172a' : '#ffffff',
            color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a',
            customClass: {
                popup: 'rounded-[2.5rem] border-none shadow-2xl p-8',
                confirmButton: 'rounded-2xl px-8 py-4 font-black text-[10px] tracking-[0.2em]',
                cancelButton: 'rounded-2xl px-8 py-4 font-black text-[10px] tracking-[0.2em] bg-slate-100 text-slate-600',
                title: 'text-2xl font-black'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }

    // Success notification
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: document.documentElement.classList.contains('dark') ? '#0f172a' : '#ffffff',
            color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a',
            customClass: {
                popup: 'rounded-[2rem] border-none shadow-xl'
            }
        });
    @endif

    // Error notification
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "{{ session('error') }}",
            background: document.documentElement.classList.contains('dark') ? '#0f172a' : '#ffffff',
            color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a',
            customClass: {
                popup: 'rounded-[2rem] border-none shadow-xl'
            }
        });
    @endif
</script>
@endpush

@push('styles')
<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush
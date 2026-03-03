@extends('layouts.admin')

@section('title', 'Profil Guru - ' . $guru->nama)
@section('breadcrumb', 'Detail Guru')

@section('content')
<div class="max-w-5xl mx-auto pb-20 space-y-8">
    <!-- Header Page & Actions -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight">Profil <span class="text-primary">Lengkap</span></h1>
            <p class="text-sm font-bold text-slate-400 uppercase tracking-[0.2em] flex items-center gap-2">
                <span class="w-8 h-[2px] bg-primary rounded-full"></span>
                Manajemen Pendidik dan Tenaga Kependidikan
            </p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.guru.index') }}" 
               class="px-5 py-3 rounded-xl bg-white dark:bg-slate-800 text-slate-400 hover:text-primary border border-slate-100 dark:border-slate-700 shadow-sm transition-all flex items-center gap-2 text-xs font-black uppercase tracking-widest">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                Kembali
            </a>
            <a href="{{ route('admin.guru.edit', $guru->id) }}" 
               class="px-5 py-3 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 transition-all flex items-center gap-2 text-xs font-black uppercase tracking-widest">
                <span class="material-symbols-outlined text-lg">edit</span>
                Edit Data
            </a>
            <form action="{{ route('admin.guru.destroy', $guru->id) }}" method="POST" class="inline" onsubmit="return confirmDelete(event, this)">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-5 py-3 rounded-xl bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/40 transition-all flex items-center gap-2 text-xs font-black uppercase tracking-widest">
                    <span class="material-symbols-outlined text-lg">delete</span>
                    Hapus
                </button>
            </form>
        </div>
    </div>

    <!-- Main Profile Card -->
    <div class="bg-white dark:bg-slate-800 rounded-[3rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden border-b-8 border-b-primary/10">
        <!-- Top Cover Overlay Style -->
        <div class="h-32 bg-gradient-to-r from-primary/5 via-indigo-500/5 to-primary/5 dark:from-primary/10 dark:via-indigo-500/10 dark:to-primary/10 relative">
            <div class="absolute -bottom-16 left-12">
                <div class="w-40 h-40 rounded-[2.5rem] bg-white dark:bg-slate-800 p-2 shadow-2xl shadow-slate-200/50 dark:shadow-none ring-4 ring-white dark:ring-slate-800 relative group">
                    @if($guru->foto)
                        <img src="{{ asset('storage/' . $guru->foto) }}" alt="{{ $guru->nama }}" class="w-full h-full object-cover rounded-[2rem]">
                    @else
                        <div class="w-full h-full rounded-[2rem] bg-slate-50 dark:bg-slate-900 flex flex-col items-center justify-center text-slate-300">
                            <span class="material-symbols-outlined text-5xl">person</span>
                        </div>
                    @endif
                    <div class="absolute -top-3 -right-3 w-10 h-10 rounded-2xl bg-white dark:bg-slate-700 shadow-lg flex items-center justify-center text-primary border border-slate-50 dark:border-slate-600">
                        <span class="material-symbols-outlined text-xl font-bold">{{ $guru->jabatan == 'guru' ? 'school' : 'admin_panel_settings' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-20 px-12 pb-12">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-8">
                <div class="space-y-4 max-w-2xl">
                    <div class="space-y-1">
                        <div class="flex items-center gap-3 flex-wrap">
                            <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{ $guru->nama }}</h2>
                            <span class="px-4 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-900 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border border-slate-100 dark:border-slate-700">
                                {{ $guru->nip ?? 'NIP TIDAK TERSEDIA' }}
                            </span>
                        </div>
                        <p class="text-sm font-bold text-slate-400 flex items-center gap-2">
                             <span class="material-symbols-outlined text-lg text-primary">work_outline</span>
                             {{ $guru->jabatan_formatted }} 
                             @if($guru->kelompok)
                                <span class="text-slate-200 dark:text-slate-700">|</span>
                                <span class="bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 px-2 rounded-lg">Wali Kelompok {{ $guru->kelompok_formatted }}</span>
                             @endif
                        </p>
                    </div>

                    <!-- Statistics Chips -->
                    <div class="flex flex-wrap gap-4 pt-2">
                        <div class="flex items-center gap-3 px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 transition-all hover:scale-105">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-500 flex items-center justify-center">
                                <span class="material-symbols-outlined text-xl">calendar_month</span>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Usia Saat Ini</p>
                                <p class="text-xs font-black text-slate-700 dark:text-slate-300 leading-none">{{ $guru->usia }} Tahun</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 transition-all hover:scale-105">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-500 flex items-center justify-center">
                                <span class="material-symbols-outlined text-xl">history_edu</span>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Pendidikan</p>
                                <p class="text-xs font-black text-slate-700 dark:text-slate-300 leading-none">{{ $guru->pendidikan_terakhir ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 transition-all hover:scale-105">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-900/20 text-amber-500 flex items-center justify-center">
                                <span class="material-symbols-outlined text-xl">event_available</span>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Terdaftar Sejak</p>
                                <p class="text-xs font-black text-slate-700 dark:text-slate-300 leading-none">{{ $guru->created_at->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact & Bio -->
                <div class="w-full md:w-80 space-y-4">
                    <div class="p-6 rounded-[2rem] bg-primary/5 border border-primary/10 space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 text-primary shadow-sm flex items-center justify-center">
                                <span class="material-symbols-outlined text-xl">contact_phone</span>
                            </div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Informasi Kontak</span>
                        </div>
                        <div class="space-y-4">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $guru->no_hp) }}" target="_blank" class="flex items-center justify-between group">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-lg text-slate-300 group-hover:text-primary transition-colors">phone_iphone</span>
                                    <span class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ $guru->no_hp }}</span>
                                </div>
                                <span class="material-symbols-outlined text-sm text-slate-300 group-hover:translate-x-1 transition-transform">open_in_new</span>
                            </a>
                            <a href="mailto:{{ $guru->email }}" class="flex items-center justify-between group">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-lg text-slate-300 group-hover:text-primary transition-colors">alternate_email</span>
                                    <span class="text-xs font-bold text-slate-600 dark:text-slate-300 truncate max-w-[150px]">{{ $guru->email }}</span>
                                </div>
                                <span class="material-symbols-outlined text-sm text-slate-300 group-hover:translate-x-1 transition-transform">open_in_new</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mt-16 pt-12 border-t border-slate-50 dark:border-slate-700/50">
                <!-- Left Side: Basic Info -->
                <div class="space-y-10">
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                            <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-widest text-[11px]">Biodata Pribadi</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-6 px-4">
                            <div class="space-y-1">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Tempat & Tanggal Lahir</p>
                                <p class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $guru->tempat_lahir ?? '-' }}, {{ $guru->tanggal_lahir_formatted }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Jenis Kelamin</p>
                                <p class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $guru->jenis_kelamin_lengkap }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Alamat Lengkap</p>
                                <p class="text-sm font-bold text-slate-700 dark:text-slate-300 leading-relaxed">{{ $guru->alamat }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Job & System Info -->
                <div class="space-y-10">
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-1.5 h-6 bg-indigo-500 rounded-full"></div>
                            <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-widest text-[11px]">Pekerjaan & Sistem</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-6 px-4">
                            <div class="space-y-1">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Jabatan Saat Ini</p>
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full {{ $guru->jabatan == 'guru' ? 'bg-emerald-500' : 'bg-blue-500' }}"></span>
                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-tight">{{ $guru->jabatan_formatted }}</p>
                                </div>
                            </div>
                            @if($guru->jabatan == 'guru')
                                <div class="space-y-1">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Tugas Utama</p>
                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Wali Kelompok {{ $guru->kelompok_formatted }}</p>
                                </div>
                            @endif
                            <div class="space-y-1">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Terakhir Diperbarui</p>
                                <p class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $guru->updated_at->translatedFormat('d F Y - H:i') }} WIB</p>
                            </div>
                        </div>
                    </div>

                    <!-- Highlight Information -->
                    <div class="p-8 rounded-[2rem] bg-indigo-50 dark:bg-indigo-900/10 border border-indigo-100 dark:border-indigo-900/20 relative overflow-hidden group">
                        <div class="relative z-10 flex items-center justify-between">
                            <div class="space-y-2">
                                <h4 class="text-xs font-black text-indigo-700 dark:text-indigo-400 uppercase tracking-widest">Status Kepegawaian</h4>
                                <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Pendidik terdaftar aktif dalam sistem akademik TK Ceria Bangsa.</p>
                            </div>
                            <span class="material-symbols-outlined text-4xl text-indigo-200 dark:text-indigo-800 group-hover:scale-110 transition-transform">verified_user</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(event, form) {
        event.preventDefault();
        Swal.fire({
            title: 'Hapus Guru?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#fe0025',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            borderRadius: '1.5rem',
            customClass: {
                title: 'font-black text-slate-900',
                confirmButton: 'px-6 py-3 rounded-xl font-bold uppercase tracking-widest text-xs',
                cancelButton: 'px-6 py-3 rounded-xl font-bold uppercase tracking-widest text-xs'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>
@endpush

@push('styles')
<style>
    .whitespace-pre-line {
        white-space: pre-line;
    }
</style>
@endpush
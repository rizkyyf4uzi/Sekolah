@extends('layouts.admin')

@section('title', 'Detail Kunjungan Tamu')
@section('breadcrumb', 'Buku Tamu / Detail')

@section('content')
<main class="max-w-6xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <h2 class="text-2xl font-extrabold text-slate-800">{{ $bukutamu->nama }}</h2>
            <span class="inline-flex items-center px-3 py-1 bg-{{ $bukutamu->status_color }}-100 text-{{ $bukutamu->status_color }}-700 rounded-full text-[10px] font-bold uppercase tracking-wide">
                <span class="w-1.5 h-1.5 rounded-full bg-{{ $bukutamu->status_color }}-500 mr-1.5"></span>
                {{ $bukutamu->status_text }}
            </span>
            @if($bukutamu->is_verified)
                <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded-full text-[10px] font-bold uppercase tracking-wide">
                    <span class="material-symbols-outlined text-[12px] mr-1">verified</span>
                    Terverifikasi
                </span>
            @endif
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.bukutamu.index') }}" class="flex items-center gap-2 px-5 py-2.5 bg-white text-slate-600 rounded-2xl font-bold text-sm hover:bg-slate-50 transition-all border border-slate-200 shadow-sm">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                Kembali
            </a>
            <a href="{{ route('admin.bukutamu.edit', $bukutamu) }}" class="flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-2xl font-bold text-sm hover:opacity-90 transition-all shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-lg">edit</span>
                Edit Data
            </a>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 p-6 bg-slate-50 rounded-3xl mb-10 border border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">calendar_today</span>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal</p>
                        <p class="text-sm font-bold text-slate-800">{{ $bukutamu->tanggal_kunjungan->format('d M Y') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">schedule</span>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Waktu</p>
                        <p class="text-sm font-bold text-slate-800">{{ $bukutamu->jam_kunjungan }} WIB</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">badge</span>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Jabatan</p>
                        <p class="text-sm font-bold text-slate-800">{{ $bukutamu->jabatan ?? '-' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">apartment</span>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Instansi</p>
                        <p class="text-sm font-bold text-slate-800">{{ $bukutamu->instansi }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <div class="lg:col-span-2 space-y-8">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <span class="w-1 h-6 bg-primary rounded-full"></span>
                            Tujuan Kunjungan
                        </h3>
                        <div class="text-slate-600 leading-relaxed p-6 bg-slate-50/50 rounded-2xl border border-slate-100 italic">
                            "{{ $bukutamu->tujuan_kunjungan }}"
                        </div>
                    </div>

                    @if($bukutamu->pesan_kesan)
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <span class="w-1 h-6 bg-primary rounded-full"></span>
                            Pesan & Kesan
                        </h3>
                        <div class="text-slate-600 leading-relaxed whitespace-pre-line">
                            {{ $bukutamu->pesan_kesan }}
                        </div>
                    </div>
                    @endif

                    <div>
                        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <span class="w-1 h-6 bg-primary rounded-full"></span>
                            Informasi Kontak
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-slate-400 shadow-sm">
                                    <span class="material-symbols-outlined text-lg">mail</span>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Email</p>
                                    <p class="text-sm font-bold text-slate-700">{{ $bukutamu->email ?? 'Tidak dicantumkan' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-slate-400 shadow-sm">
                                    <span class="material-symbols-outlined text-lg">call</span>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Telepon/WA</p>
                                    <p class="text-sm font-bold text-slate-700">{{ $bukutamu->telepon ?? 'Tidak dicantumkan' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-8">
                    <div class="p-6 bg-primary/5 rounded-3xl border border-primary/10">
                        <h4 class="text-sm font-bold text-primary mb-4">Aksi Cepat</h4>
                        <div class="space-y-3">
                            @if(!$bukutamu->is_verified)
                            <form action="{{ route('admin.bukutamu.verify', $bukutamu) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-3 bg-white text-primary rounded-2xl text-xs font-bold hover:bg-primary hover:text-white transition-all border border-primary/20 shadow-sm flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-lg">verified</span>
                                    Verifikasi Data
                                </button>
                            </form>
                            @endif

                            <div class="relative group">
                                <button class="w-full py-3 bg-white text-slate-600 rounded-2xl text-xs font-bold hover:bg-slate-50 transition-all border border-slate-200 shadow-sm flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-lg">sync_alt</span>
                                    Ubah Status
                                </button>
                                <div class="absolute right-0 left-0 mt-2 bg-white rounded-2xl shadow-xl border border-slate-100 hidden group-hover:block z-10 overflow-hidden">
                                    @foreach(['pending', 'approved', 'completed', 'rejected'] as $st)
                                        @if($st !== $bukutamu->status)
                                        <form action="{{ route('admin.bukutamu.index') }}/{{ $bukutamu->id }}/status" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="{{ $st }}">
                                            <button type="submit" class="w-full px-5 py-3 text-left text-xs font-bold text-slate-600 hover:bg-slate-50 transition-all border-b border-slate-50 last:border-0 capitalize">
                                                Set ke {{ $st }}
                                            </button>
                                        </form>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <form action="{{ route('admin.bukutamu.destroy', $bukutamu) }}" method="POST" onsubmit="return confirm('Hapus data ini secara permanen?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full py-3 bg-red-50 text-red-600 rounded-2xl text-xs font-bold hover:bg-red-500 hover:text-white transition-all flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-lg">delete</span>
                                    Hapus Data
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 text-center">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Dicatat Oleh</p>
                        <p class="text-sm font-bold text-slate-700">{{ $bukutamu->user->name ?? 'Sistem' }}</p>
                        <p class="text-[10px] text-slate-400 mt-1 italic">{{ $bukutamu->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

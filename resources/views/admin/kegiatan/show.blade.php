@extends('layouts.admin')

@section('title', 'Detail Kegiatan Sekolah')
@section('breadcrumb', 'Kegiatan Sekolah / Detail')

@section('content')
<main class="max-w-6xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <h2 class="text-2xl font-extrabold text-slate-800">{{ $kegiatan->nama_kegiatan }}</h2>
            @if($kegiatan->is_published)
                <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded-full text-[10px] font-bold uppercase tracking-wide">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                    Published
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-[10px] font-bold uppercase tracking-wide">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                    Draft
                </span>
            @endif
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto">
            <a href="{{ route('admin.kegiatan.index') }}" class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-5 py-2.5 bg-white text-slate-600 rounded-2xl font-bold text-sm hover:bg-slate-50 transition-all border border-slate-200 shadow-sm">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                Kembali
            </a>
            <a href="{{ route('admin.kegiatan.edit', $kegiatan) }}" class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-5 py-2.5 bg-primary text-white rounded-2xl font-bold text-sm hover:opacity-90 transition-all shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-lg">edit</span>
                Edit Kegiatan
            </a>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="relative h-72 w-full overflow-hidden">
            @if($kegiatan->banner_path)
                <img src="{{ $kegiatan->banner_url }}" alt="{{ $kegiatan->nama_kegiatan }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-slate-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-slate-300 text-6xl">image</span>
                </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent flex items-end p-8">
                <p class="text-white/80 font-medium flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">photo_camera</span>
                    Banner Kegiatan Resmi
                </p>
            </div>
        </div>

        <div class="p-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 p-6 bg-slate-50 rounded-3xl mb-10 border border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary shrink-0">
                        <span class="material-symbols-outlined">calendar_today</span>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal</p>
                        <p class="text-sm font-bold text-slate-800">
                            {{ $kegiatan->tanggal_mulai->format('d M Y') }}
                            @if($kegiatan->tanggal_selesai)
                                - {{ $kegiatan->tanggal_selesai->format('d M Y') }}
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary shrink-0">
                        <span class="material-symbols-outlined">schedule</span>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Waktu</p>
                        <p class="text-sm font-bold text-slate-800">
                            @if($kegiatan->waktu_mulai)
                                {{ $kegiatan->waktu_mulai->format('H:i') }}
                                @if($kegiatan->waktu_selesai)
                                    - {{ $kegiatan->waktu_selesai->format('H:i') }}
                                @endif
                                WIB
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary shrink-0">
                        <span class="material-symbols-outlined">location_on</span>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Lokasi</p>
                        <p class="text-sm font-bold text-slate-800">{{ $kegiatan->lokasi }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary shrink-0">
                        <span class="material-symbols-outlined">category</span>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kategori</p>
                        <p class="text-sm font-bold text-slate-800">{{ $kegiatan->kategori }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <div class="lg:col-span-2 space-y-8">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <span class="w-1 h-6 bg-primary rounded-full"></span>
                            Deskripsi Kegiatan
                        </h3>
                        <div class="text-slate-600 leading-relaxed space-y-4 whitespace-pre-line">
                            {{ $kegiatan->deskripsi ?? 'Tidak ada deskripsi kegiatan.' }}
                        </div>
                    </div>
                    
                    {{-- Target Peserta Placeholder --}}
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <span class="w-1 h-6 bg-primary rounded-full"></span>
                            Target Peserta
                        </h3>
                        <div class="flex flex-wrap gap-3">
                            <span class="px-4 py-2 bg-lavender text-primary rounded-xl text-sm font-semibold">Siswa</span>
                            <span class="px-4 py-2 bg-lavender text-primary rounded-xl text-sm font-semibold">Guru</span>
                            <span class="px-4 py-2 bg-lavender text-primary rounded-xl text-sm font-semibold">Orang Tua</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-8">
                    {{-- Lampiran Placeholder --}}
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <span class="w-1 h-6 bg-primary rounded-full"></span>
                            Lampiran Berkas (Opsional)
                        </h3>
                        <div class="space-y-3">
                            <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl italic text-xs text-slate-400 text-center">
                                Belum ada lampiran berkas
                            </div>
                        </div>
                    </div>

                    <div class="p-6 bg-primary/5 rounded-3xl border border-primary/10">
                        <h4 class="text-sm font-bold text-primary mb-2">Informasi Publikasi</h4>
                        @if($kegiatan->is_published)
                            <p class="text-xs text-slate-500 leading-relaxed mb-4">Kegiatan ini sudah dipublikasikan dan dapat dilihat oleh publik di website sekolah.</p>
                            <form action="{{ route('admin.kegiatan.toggle-publish', $kegiatan) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="w-full py-2.5 bg-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-300 transition-all">Sembunyikan (Set Draft)</button>
                            </form>
                        @else
                            <p class="text-xs text-slate-500 leading-relaxed mb-4">Kegiatan ini saat ini berstatus Draft dan tidak akan muncul di website publik sampai Anda mengubah statusnya menjadi Public.</p>
                            <form action="{{ route('admin.kegiatan.toggle-publish', $kegiatan) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="w-full py-2.5 bg-primary text-white rounded-xl text-xs font-bold hover:bg-primary/90 transition-all">Publish Sekarang</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

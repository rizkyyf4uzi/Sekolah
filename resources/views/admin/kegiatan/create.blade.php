@extends('layouts.admin')

@section('title', 'Tambah Kegiatan Sekolah Baru')
@section('breadcrumb', 'Kegiatan Sekolah / Tambah')

@push('styles')
<style>
    .toggle-checkbox:checked {
        right: 0;
        border-color: #6B46C1;
        background-color: #6B46C1;
    }
    .toggle-checkbox:checked + .toggle-label {
        background-color: rgba(107, 70, 193, 0.2);
    }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <form action="{{ route('admin.kegiatan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Nama Kegiatan</label>
                            <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" required
                                   class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 text-sm transition-all text-slate-700" 
                                   placeholder="Masukkan nama kegiatan...">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Kategori Kegiatan</label>
                            <select name="kategori" required
                                    class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 text-sm transition-all text-slate-700">
                                <option value="" disabled selected>Pilih Kategori</option>
                                <option value="Academic Event" {{ old('kategori') == 'Academic Event' ? 'selected' : '' }}>Academic Event</option>
                                <option value="Extracurricular" {{ old('kategori') == 'Extracurricular' ? 'selected' : '' }}>Extracurricular</option>
                                <option value="Public Information" {{ old('kategori') == 'Public Information' ? 'selected' : '' }}>Public Information</option>
                                <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Lokasi</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">location_on</span>
                                <input type="text" name="lokasi" value="{{ old('lokasi') }}" required
                                       class="w-full pl-12 pr-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 text-sm transition-all text-slate-700" 
                                       placeholder="Masukkan lokasi kegiatan...">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Tanggal Mulai</label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg">calendar_today</span>
                                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                                           class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 text-sm transition-all text-slate-700">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Tanggal Selesai</label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg">calendar_today</span>
                                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                                           class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 text-sm transition-all text-slate-700">
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Waktu Mulai</label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg">schedule</span>
                                    <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai') }}"
                                           class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 text-sm transition-all text-slate-700">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Waktu Selesai</label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg">schedule</span>
                                    <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai') }}"
                                           class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 text-sm transition-all text-slate-700">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Banner/Foto Kegiatan</label>
                            <label id="banner-container" class="border-2 border-dashed border-slate-200 rounded-3xl p-8 text-center bg-slate-50/50 hover:bg-slate-50 transition-all group cursor-pointer block relative overflow-hidden">
                                <div id="banner-placeholder">
                                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                                        <span class="material-symbols-outlined text-primary text-3xl">cloud_upload</span>
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-700 mb-1">Klik atau seret file banner</h4>
                                    <p class="text-xs text-slate-400">Rekomendasi ukuran 1200 x 600 px (Maks. 5MB)</p>
                                </div>
                                <div id="banner-preview" class="hidden absolute inset-0">
                                    <img src="" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                        <p class="text-white text-xs font-bold">Ganti Banner</p>
                                    </div>
                                </div>
                                <input type="file" name="banner" id="banner-input" class="hidden" accept="image/*">
                            </label>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Deskripsi Kegiatan</label>
                            <textarea name="deskripsi" rows="6"
                                      class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 text-sm transition-all text-slate-700 resize-none" 
                                      placeholder="Tuliskan rincian kegiatan sekolah...">{{ old('deskripsi') }}</textarea>
                        </div>
                        <div class="flex items-center justify-between p-5 bg-lavender/30 rounded-2xl border border-primary/10">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm">
                                    <span class="material-symbols-outlined text-primary">visibility</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">Status Publikasi</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Tentukan keterlihatan kegiatan</p>
                                </div>
                            </div>
                            <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                                <input type="hidden" name="is_published" value="0">
                                <input type="checkbox" name="is_published" value="1" id="toggle" 
                                       class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer border-slate-300" 
                                       {{ old('is_published') ? 'checked' : '' }}>
                                <label class="toggle-label block overflow-hidden h-6 rounded-full bg-slate-300 cursor-pointer" for="toggle"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50/50 px-8 py-6 flex items-center justify-end gap-4 border-t border-slate-100">
                <a href="{{ route('admin.kegiatan.index') }}" 
                   class="px-8 py-3.5 text-slate-600 font-bold text-sm hover:bg-slate-100 rounded-2xl transition-all">
                    Batal
                </a>
                <button type="submit" class="px-8 py-3.5 bg-primary text-white rounded-2xl font-bold text-sm hover:opacity-90 transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">save</span>
                    Simpan Kegiatan
                </button>
            </div>
        </form>
    </div>
    <div class="bg-blue-50 border border-blue-100 p-5 rounded-3xl flex items-start gap-4">
        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-blue-500 shadow-sm shrink-0">
            <span class="material-symbols-outlined">info</span>
        </div>
        <div>
            <h4 class="text-sm font-bold text-blue-900">Tips Pengisian</h4>
            <p class="text-xs text-blue-700/80 leading-relaxed mt-1">Pastikan banner yang diunggah memiliki kualitas yang baik karena akan ditampilkan pada halaman depan portal sekolah. Kegiatan dengan status "Public" akan langsung dapat dilihat oleh orang tua dan siswa.</p>
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.getElementById('banner-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('banner-preview').querySelector('img').src = event.target.result;
                document.getElementById('banner-preview').classList.remove('hidden');
                document.getElementById('banner-placeholder').classList.add('hidden');
                document.getElementById('banner-container').classList.remove('p-8');
                document.getElementById('banner-container').classList.add('aspect-video');
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection

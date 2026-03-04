@extends('layouts.admin')

@section('title', 'Tambah Buku Tamu Baru')
@section('breadcrumb', 'Buku Tamu / Tambah')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-50">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-2xl bg-lavender flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">person_add</span>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Formulir Kunjungan</h2>
                    <p class="text-sm text-slate-500">Lengkapi data di bawah ini untuk mencatat tamu baru.</p>
                </div>
            </div>

            <form action="{{ route('admin.bukutamu.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest" for="nama">Nama Pengunjung <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">person</span>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                                   class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all" 
                                   placeholder="Masukkan nama lengkap">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest" for="telepon">No. HP/Kontak <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">call</span>
                            <input type="tel" name="telepon" id="telepon" value="{{ old('telepon') }}" required
                                   class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all" 
                                   placeholder="Contoh: 081234567890">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest" for="instansi">Instansi/Asal <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">corporate_fare</span>
                            <input type="text" name="instansi" id="instansi" value="{{ old('instansi') }}" required
                                   class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all" 
                                   placeholder="Nama sekolah/instansi asal">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest" for="tujuan_kunjungan">Tujuan Kunjungan <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">flag</span>
                            <select name="tujuan_kunjungan" id="tujuan_kunjungan" required
                                    class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all appearance-none">
                                <option value="" disabled selected>Pilih tujuan kunjungan</option>
                                <option value="Konsultasi Kurikulum" {{ old('tujuan_kunjungan') == 'Konsultasi Kurikulum' ? 'selected' : '' }}>Konsultasi Kurikulum</option>
                                <option value="Pendaftaran Siswa Baru" {{ old('tujuan_kunjungan') == 'Pendaftaran Siswa Baru' ? 'selected' : '' }}>Pendaftaran Siswa Baru</option>
                                <option value="Dinas Pendidikan" {{ old('tujuan_kunjungan') == 'Dinas Pendidikan' ? 'selected' : '' }}>Dinas Pendidikan</option>
                                <option value="Keperluan Lainnya" {{ old('tujuan_kunjungan') == 'Keperluan Lainnya' ? 'selected' : '' }}>Keperluan Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest" for="email">Email (Opsional)</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">mail</span>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                   class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all" 
                                   placeholder="Alamat email aktif">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest" for="jabatan">Jabatan (Opsional)</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">badge</span>
                            <input type="text" name="jabatan" id="jabatan" value="{{ old('jabatan') }}"
                                   class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all" 
                                   placeholder="Jabatan pengunjung">
                        </div>
                    </div>

                    <div class="space-y-2 md:col-span-2">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest" for="waktu_kunjungan">Tanggal & Waktu Kedatangan</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">event_available</span>
                            <input type="datetime-local" name="waktu_kunjungan" id="waktu_kunjungan" required
                                   value="{{ old('waktu_kunjungan', date('Y-m-d\TH:i')) }}"
                                   class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all">
                        </div>
                    </div>

                    <div class="space-y-2 md:col-span-2">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest" for="pesan_kesan">Detail Keperluan</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-4 text-slate-400 text-xl">description</span>
                            <textarea name="pesan_kesan" id="pesan_kesan" rows="4"
                                      class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all min-h-[120px]" 
                                      placeholder="Jelaskan secara spesifik keperluan kunjungan Anda...">{{ old('pesan_kesan') }}</textarea>
                        </div>
                    </div>

                    <input type="hidden" name="status" value="completed">
                    <input type="hidden" name="is_verified" value="0">
                </div>

                <div class="pt-6 flex flex-col sm:flex-row items-center justify-end gap-3 border-t border-slate-100">
                    <a href="{{ route('admin.bukutamu.index') }}" 
                       class="w-full sm:w-auto px-8 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all active:scale-[0.98] text-center">
                        Batal
                    </a>
                    <button type="submit" 
                            class="w-full sm:w-auto px-8 py-3 bg-primary text-white rounded-xl font-bold text-sm hover:shadow-lg hover:shadow-primary/30 transition-all active:scale-[0.98]">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-8 flex items-center justify-center gap-6 opacity-60">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-sm text-slate-400">verified_user</span>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Secure Entry</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-sm text-slate-400">history</span>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Auto Timestamp</span>
        </div>
    </div>
</div>
@endsection

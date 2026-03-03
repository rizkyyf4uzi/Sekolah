@extends('layouts.admin')

@push('meta')
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
@endpush

@section('content')
<div class="min-h-screen bg-transparent p-0">
    <div class="max-w-full mx-auto">
        <!-- Breadcrumb -->
        <nav aria-label="Breadcrumb" class="flex mb-4 text-xs font-medium text-slate-400 uppercase tracking-widest">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li><a href="#" class="hover:text-primary transition-colors">Master Data</a></li>
                <li><span class="mx-2">/</span></li>
                <li><a href="{{ route('admin.siswa.siswa-aktif.index') }}" class="hover:text-primary transition-colors">Data Siswa</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-slate-600 dark:text-slate-300">Tambah Siswa Baru</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Tambah Siswa Baru</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Formulir Pendaftaran Siswa Baru - Tahun Ajaran {{ $tahunAjaranAktif->tahun_ajaran }}</p>
            </div>
        </div>

        <form action="{{ route('admin.siswa.siswa-aktif.store') }}" method="POST" enctype="multipart/form-data" id="siswaForm" class="space-y-8">
            @csrf
            
            <!-- A. DATA CALON SISWA -->
            <section class="bg-white dark:bg-slate-800 rounded-2xl p-8 border border-slate-100 dark:border-slate-700 shadow-sm mb-8">
                <div class="text-xl font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-3 border-b pb-4">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined">person</span>
                    </div>
                    A. DATA CALON SISWA
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Nama Lengkap Anak -->
                    <div class="flex flex-col gap-2 md:col-span-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Nama Lengkap Anak <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white @error('nama_lengkap') border-red-500 @enderror" 
                               placeholder="Nama sesuai akta kelahiran">
                        @error('nama_lengkap') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Nama Panggilan -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Nama Panggilan</label>
                        <input type="text" name="nama_panggilan" value="{{ old('nama_panggilan') }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" 
                               placeholder="Nama panggilan sehari-hari">
                    </div>

                    <!-- NIK Anak -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">NIK Anak <span class="text-red-500">*</span></label>
                        <input type="text" name="nik" value="{{ old('nik') }}" required 
                               maxlength="16" minlength="16"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white @error('nik') border-red-500 @enderror numeric-only" 
                               placeholder="16 digit NIK">
                        @error('nik') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- NIS (Keep for school records) -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">NIS <span class="text-red-500">*</span></label>
                        <input type="text" name="nis" value="{{ old('nis') }}" required
                               maxlength="15" minlength="5"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white @error('nis') border-red-500 @enderror numeric-only" 
                               placeholder="Masukkan NIS">
                        @error('nis') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- NISN -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">NISN <span class="text-red-500">*</span></label>
                        <input type="text" name="nisn" value="{{ old('nisn') }}" required
                               maxlength="10" minlength="10"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white @error('nisn') border-red-500 @enderror numeric-only" 
                               placeholder="10 digit NISN">
                        @error('nisn') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tempat Lahir -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Tempat Lahir <span class="text-red-500">*</span></label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" 
                               placeholder="Kota/kabupaten">
                    </div>

                    <!-- Tanggal Lahir -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required max="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white">
                    </div>

                    <!-- Anak Ke -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Anak Ke <span class="text-red-500">*</span></label>
                        <input type="number" name="anak_ke" value="{{ old('anak_ke') }}" required min="1"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" 
                               placeholder="Contoh: 1, 2, 3">
                    </div>

                    <!-- Bahasa Sehari-hari -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Bahasa Sehari-hari <span class="text-red-500">*</span></label>
                        <input type="text" name="bahasa_sehari_hari" value="{{ old('bahasa_sehari_hari') }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" 
                               placeholder="Contoh: Indonesia, Sunda, Jawa">
                    </div>

                    <!-- Jarak Rumah ke Sekolah -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Jarak Rumah ke Sekolah (meter)</label>
                        <input type="number" name="jarak_rumah_ke_sekolah" value="{{ old('jarak_rumah_ke_sekolah') }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" 
                               placeholder="Contoh: 500">
                    </div>

                    <!-- Waktu Tempuh -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Waktu Tempuh (menit)</label>
                        <input type="number" name="waktu_tempuh_ke_sekolah" value="{{ old('waktu_tempuh_ke_sekolah') }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" 
                               placeholder="Contoh: 15">
                    </div>

                    <!-- Berat Badan -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Berat Badan (kg)</label>
                        <input type="number" step="0.1" name="berat_badan" value="{{ old('berat_badan') }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" 
                               placeholder="Contoh: 15.5">
                    </div>

                    <!-- Tinggi Badan -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Tinggi Badan (cm)</label>
                        <input type="number" step="0.1" name="tinggi_badan" value="{{ old('tinggi_badan') }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" 
                               placeholder="Contoh: 95.5">
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="jenis_kelamin" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <!-- Agama -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Agama <span class="text-red-500">*</span></label>
                        <select name="agama" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white">
                            <option value="">Pilih Agama</option>
                            @foreach(['Islam', 'Kristen Protestan', 'Kristen Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya'] as $agama)
                                <option value="{{ $agama }}" {{ old('agama') == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tinggal Bersama -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Tinggal Bersama <span class="text-red-500">*</span></label>
                        <select name="tinggal_bersama" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white">
                            <option value="">Pilih</option>
                            @foreach(['Ayah dan Ibu', 'Ayah', 'Ibu', 'Keluarga Ayah', 'Keluarga Ibu', 'Lainnya'] as $tinggal)
                                <option value="{{ $tinggal }}" {{ old('tinggal_bersama') == $tinggal ? 'selected' : '' }}>{{ $tinggal }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Tempat Tinggal -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Status Tempat Tinggal <span class="text-red-500">*</span></label>
                        <select name="status_tempat_tinggal" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white">
                            <option value="">Pilih</option>
                            @foreach(['Milik Sendiri', 'Milik Keluarga', 'Kontrakan'] as $status)
                                <option value="{{ $status }}" {{ old('status_tempat_tinggal') == $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Golongan Darah -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Golongan Darah</label>
                        <select name="golongan_darah" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white">
                            <option value="">Pilih Golongan Darah</option>
                            @foreach(['A', 'B', 'AB', 'O'] as $gol)
                                <option value="{{ $gol }}" {{ old('golongan_darah') == $gol ? 'selected' : '' }}>{{ $gol }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Penyakit yang Pernah Diderita -->
                    <div class="flex flex-col gap-2 md:col-span-3">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Penyakit yang Pernah Diderita</label>
                        <textarea name="penyakit_pernah_diderita" rows="2" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" placeholder="Contoh: Demam berdarah, cacar, asma, dll">{{ old('penyakit_pernah_diderita') }}</textarea>
                    </div>

                    <!-- Imunisasi yang Pernah Diterima -->
                    <div class="flex flex-col gap-2 md:col-span-3">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Imunisasi yang Pernah Diterima</label>
                        <textarea name="imunisasi" rows="2" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" placeholder="Contoh: BCG, Polio, Campak, Hepatitis, DPT">{{ old('imunisasi') }}</textarea>
                    </div>

                    <!-- Foto Siswa (Keeping existing UI) -->
                    <div class="flex flex-col gap-2 md:col-span-3 border-t pt-6 mt-4">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Foto Siswa</label>
                        <div class="flex items-start gap-4">
                            <div class="relative group">
                                <div id="foto-preview" class="w-24 h-24 rounded-2xl bg-slate-100 dark:bg-slate-900 border-2 border-dashed border-slate-200 dark:border-slate-700 flex items-center justify-center overflow-hidden">
                                    <span class="material-symbols-outlined text-slate-400 text-3xl">add_a_photo</span>
                                </div>
                                <button type="button" id="remove-foto" class="absolute -top-2 -right-2 bg-red-500 text-white p-1 rounded-full shadow-lg hidden hover:bg-red-600 transition-all">
                                    <span class="material-symbols-outlined text-sm">close</span>
                                </button>
                            </div>
                            <div class="flex-1">
                                <input type="file" name="foto" id="foto-input" accept="image/*"
                                       class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-all cursor-pointer">
                                <p class="text-[10px] text-slate-400 mt-2 leading-relaxed">Format: JPG, JPEG, PNG. Maksimal 2MB. Rekomendasi rasio 3:4 atau 1:1.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- B. ALAMAT LENGKAP -->
            <section class="bg-white dark:bg-slate-800 rounded-2xl p-8 border border-slate-100 dark:border-slate-700 shadow-sm mb-8">
                <div class="text-xl font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-3 border-b pb-4">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined">location_on</span>
                    </div>
                    B. ALAMAT LENGKAP
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Provinsi -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Provinsi <span class="text-red-500">*</span></label>
                        <input type="text" name="provinsi" value="{{ old('provinsi', 'Jawa Barat') }}" required
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" 
                               placeholder="Provinsi">
                    </div>

                    <!-- Kota/Kabupaten -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kota/Kabupaten <span class="text-red-500">*</span></label>
                        <input type="text" name="kota_kabupaten" value="{{ old('kota_kabupaten', 'Kota Bandung') }}" required
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" 
                               placeholder="Kota/Kabupaten">
                    </div>

                    <!-- Kecamatan -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kecamatan <span class="text-red-500">*</span></label>
                        <input type="text" name="kecamatan" value="{{ old('kecamatan') }}" required
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" 
                               placeholder="Kecamatan">
                    </div>

                    <!-- Kelurahan/Desa -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelurahan/Desa <span class="text-red-500">*</span></label>
                        <input type="text" name="kelurahan" value="{{ old('kelurahan') }}" required
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" 
                               placeholder="Kelurahan/Desa">
                    </div>

                    <!-- Nama Jalan -->
                    <div class="flex flex-col gap-2 md:col-span-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Nama Jalan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_jalan" value="{{ old('nama_jalan') }}" required
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" 
                               placeholder="Contoh: Jl. Terusan PSM No. 1A, RT 01 RW 02">
                    </div>

                    <!-- Textarea Alamat Lengkap (Domisili/Sekarang) -->
                    <div class="flex flex-col gap-2 md:col-span-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <textarea name="alamat" rows="2" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" placeholder="Alamat lengkap tempat tinggal sekarang">{{ old('alamat') }}</textarea>
                    </div>

                    <!-- Checkbox Alamat KK -->
                    <div class="md:col-span-2 flex items-center gap-3 py-2">
                        <input type="checkbox" name="alamat_kk_sama" id="alamat_kk_sama" value="1" {{ old('alamat_kk_sama', true) ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-slate-300 text-primary focus:ring-primary transition-all">
                        <label for="alamat_kk_sama" class="text-sm font-medium text-slate-700 dark:text-slate-300 cursor-pointer text-primary">
                            Alamat di Kartu Keluarga (KK) sama dengan alamat di atas
                        </label>
                    </div>

                    <!-- Fields Alamat KK (Conditional) -->
                    <div id="section_alamat_kk" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100 dark:border-slate-700 {{ old('alamat_kk_sama', true) ? 'hidden' : '' }}">
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Provinsi (KK)</label>
                            <input type="text" name="provinsi_kk" value="{{ old('provinsi_kk') }}" 
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" 
                                   placeholder="Provinsi (KK)">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kota/Kabupaten (KK)</label>
                            <input type="text" name="kota_kabupaten_kk" value="{{ old('kota_kabupaten_kk') }}" 
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" 
                                   placeholder="Kota/Kabupaten (KK)">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kecamatan (KK)</label>
                            <input type="text" name="kecamatan_kk" value="{{ old('kecamatan_kk') }}" 
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" 
                                   placeholder="Kecamatan (KK)">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelurahan/Desa (KK)</label>
                            <input type="text" name="kelurahan_kk" value="{{ old('kelurahan_kk') }}" 
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" 
                                   placeholder="Kelurahan/Desa (KK)">
                        </div>
                        <div class="flex flex-col gap-2 md:col-span-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Nama Jalan (KK)</label>
                            <input type="text" name="nama_jalan_kk" value="{{ old('nama_jalan_kk') }}" 
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" 
                                   placeholder="Alamat lengkap sesuai KK">
                        </div>
                        <div class="flex flex-col gap-2 md:col-span-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Alamat KK Lengkap</label>
                            <textarea name="alamat_kk" rows="2" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" placeholder="Alamat lengkap sesuai KK (opsional)">{{ old('alamat_kk') }}</textarea>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Data Tambahan & Kesehatan -->
            <section class="bg-white dark:bg-slate-800 rounded-2xl p-8 border border-slate-100 dark:border-slate-700 shadow-sm mb-8">
                <div class="text-xl font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined">medical_services</span>
                    </div>
                    Data Tambahan & Kesehatan
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Anak Ke- -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Anak Ke-</label>
                        <input type="number" name="anak_ke" value="{{ old('anak_ke') }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white numeric-only" 
                               placeholder="0">
                    </div>

                    <!-- Berat Badan -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Berat Badan (kg)</label>
                        <input type="number" step="0.1" name="berat_badan" value="{{ old('berat_badan') }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white numeric-only" 
                               placeholder="0">
                    </div>

                    <!-- Tinggi Badan -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Tinggi Badan (cm)</label>
                        <input type="number" step="0.1" name="tinggi_badan" value="{{ old('tinggi_badan') }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white numeric-only" 
                               placeholder="0">
                    </div>

                    <!-- Golongan Darah -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Golongan Darah</label>
                        <select name="golongan_darah" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white">
                            <option value="">Pilih</option>
                            @foreach(['A', 'B', 'AB', 'O'] as $gol)
                                <option value="{{ $gol }}" {{ old('golongan_darah') == $gol ? 'selected' : '' }}>{{ $gol }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tinggal Bersama -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Tinggal Bersama</label>
                        <select name="tinggal_bersama" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white">
                            @foreach(['Orang Tua', 'Wali', 'Asrama', 'Lainnya'] as $tinggal)
                                <option value="{{ $tinggal }}" {{ old('tinggal_bersama') == $tinggal ? 'selected' : '' }}>{{ $tinggal }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Riwayat Penyakit -->
                    <div class="flex flex-col gap-2 md:col-span-3">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Riwayat Penyakit</label>
                        <input type="text" name="penyakit_pernah_diderita" value="{{ old('penyakit_pernah_diderita') }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" 
                               placeholder="Pisahkan dengan koma jika lebih dari satu">
                    </div>

                    <!-- Riwayat Imunisasi -->
                    <div class="flex flex-col gap-2 md:col-span-4">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Riwayat Imunisasi</label>
                        <input type="text" name="imunisasi" value="{{ old('imunisasi') }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" 
                               placeholder="DPT, Polio, Campak, dsb.">
                    </div>
                </div>
            </section>

            <!-- C. & D. DATA ORANG TUA -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- C. DATA AYAH -->
                <section class="bg-white dark:bg-slate-800 rounded-2xl p-8 border border-slate-100 dark:border-slate-700 shadow-sm">
                    <div class="text-xl font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-3 border-b pb-4">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined">man</span>
                        </div>
                        C. DATA AYAH
                    </div>
                    <div class="space-y-4">
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Nama Lengkap Ayah <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_ayah" value="{{ old('nama_ayah') }}" required 
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" 
                                   placeholder="Nama Lengkap Ayah">
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">NIK Ayah <span class="text-red-500">*</span></label>
                            <input type="text" name="nik_ayah" value="{{ old('nik_ayah') }}" required 
                                   maxlength="16" minlength="16"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white numeric-only" 
                                   placeholder="16 digit NIK">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Tempat Lahir Ayah <span class="text-red-500">*</span></label>
                                <input type="text" name="tempat_lahir_ayah" value="{{ old('tempat_lahir_ayah') }}" required 
                                       class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" 
                                       placeholder="Tempat Lahir">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Tanggal Lahir Ayah <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_lahir_ayah" value="{{ old('tanggal_lahir_ayah') }}" required max="{{ date('Y-m-d') }}"
                                       class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white">
                            </div>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Pendidikan Ayah</label>
                            <select name="pendidikan_ayah" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white">
                                <option value="">Pilih Pendidikan</option>
                                @foreach(['Tidak Sekolah', 'SD', 'SMP', 'SMA', 'D1', 'D2', 'D3', 'S1', 'S2', 'S3'] as $p)
                                    <option value="{{ $p }}" {{ old('pendidikan_ayah') == $p ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Pekerjaan Ayah</label>
                            <select name="pekerjaan_ayah" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white">
                                <option value="">Pilih Pekerjaan</option>
                                @foreach(['Pekerja Informal', 'Wirausaha', 'Pegawai Swasta', 'Pegawai Negeri Sipil (PNS)'] as $job)
                                    <option value="{{ $job }}" {{ old('pekerjaan_ayah') == $job ? 'selected' : '' }}>{{ $job }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Penghasilan per Bulan Ayah</label>
                            <select name="penghasilan_per_bulan_ayah" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white">
                                <option value="">Pilih Penghasilan</option>
                                <option value="< Rp 1.000.000" {{ old('penghasilan_per_bulan_ayah') == '< Rp 1.000.000' ? 'selected' : '' }}>< Rp 1.000.000 (< 1 juta)</option>
                                <option value="Rp 1.000.000 - 3.000.000" {{ old('penghasilan_per_bulan_ayah') == 'Rp 1.000.000 - 3.000.000' ? 'selected' : '' }}>Rp 1.000.000 - 3.000.000 (1-3 juta)</option>
                                <option value="Rp 3.000.000 - 5.000.000" {{ old('penghasilan_per_bulan_ayah') == 'Rp 3.000.000 - 5.000.000' ? 'selected' : '' }}>Rp 3.000.000 - 5.000.000 (3-5 juta)</option>
                                <option value="Rp 5.000.000 - 10.000.000" {{ old('penghasilan_per_bulan_ayah') == 'Rp 5.000.000 - 10.000.000' ? 'selected' : '' }}>Rp 5.000.000 - 10.000.000 (5-10 juta)</option>
                                <option value="> Rp 10.000.000" {{ old('penghasilan_per_bulan_ayah') == '> Rp 10.000.000' ? 'selected' : '' }}>> Rp 10.000.000 (> 10 juta)</option>
                            </select>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Nomor Telepon/WA Ayah <span class="text-red-500">*</span></label>
                            <input type="text" name="nomor_telepon_ayah" value="{{ old('nomor_telepon_ayah') }}" required 
                                   maxlength="15" minlength="10"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white numeric-only" 
                                   placeholder="081234567890">
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Email Ayah</label>
                            <input type="email" name="email_ayah" value="{{ old('email_ayah') }}" 
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" 
                                   placeholder="ayah@email.com">
                        </div>
                    </div>
                </section>

                <!-- D. DATA IBU -->
                <section class="bg-white dark:bg-slate-800 rounded-2xl p-8 border border-slate-100 dark:border-slate-700 shadow-sm">
                    <div class="text-xl font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-3 border-b pb-4">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined">woman</span>
                        </div>
                        D. DATA IBU
                    </div>
                    <div class="space-y-4">
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Nama Lengkap Ibu <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_ibu" value="{{ old('nama_ibu') }}" required 
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" 
                                   placeholder="Nama Lengkap Ibu">
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">NIK Ibu <span class="text-red-500">*</span></label>
                            <input type="text" name="nik_ibu" value="{{ old('nik_ibu') }}" required 
                                   maxlength="16" minlength="16"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white numeric-only" 
                                   placeholder="16 digit NIK">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Tempat Lahir Ibu <span class="text-red-500">*</span></label>
                                <input type="text" name="tempat_lahir_ibu" value="{{ old('tempat_lahir_ibu') }}" required 
                                       class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" 
                                       placeholder="Tempat Lahir">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Tanggal Lahir Ibu <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_lahir_ibu" value="{{ old('tanggal_lahir_ibu') }}" required max="{{ date('Y-m-d') }}"
                                       class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white">
                            </div>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Pendidikan Ibu</label>
                            <select name="pendidikan_ibu" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white">
                                <option value="">Pilih Pendidikan</option>
                                @foreach(['Tidak Sekolah', 'SD', 'SMP', 'SMA', 'D1', 'D2', 'D3', 'S1', 'S2', 'S3'] as $p)
                                    <option value="{{ $p }}" {{ old('pendidikan_ibu') == $p ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Pekerjaan Ibu</label>
                            <select name="pekerjaan_ibu" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white">
                                <option value="">Pilih Pekerjaan</option>
                                @foreach(['Ibu Rumah Tangga', 'Pekerja Informal', 'Wirausaha', 'Pegawai Swasta', 'Pegawai Negeri Sipil (PNS)'] as $job)
                                    <option value="{{ $job }}" {{ old('pekerjaan_ibu') == $job ? 'selected' : '' }}>{{ $job }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Penghasilan per Bulan Ibu</label>
                            <select name="penghasilan_per_bulan_ibu" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white">
                                <option value="">Pilih Penghasilan</option>
                                <option value="< Rp 1.000.000" {{ old('penghasilan_per_bulan_ibu') == '< Rp 1.000.000' ? 'selected' : '' }}>< Rp 1.000.000 (< 1 juta)</option>
                                <option value="Rp 1.000.000 - 3.000.000" {{ old('penghasilan_per_bulan_ibu') == 'Rp 1.000.000 - 3.000.000' ? 'selected' : '' }}>Rp 1.000.000 - 3.000.000 (1-3 juta)</option>
                                <option value="Rp 3.000.000 - 5.000.000" {{ old('penghasilan_per_bulan_ibu') == 'Rp 3.000.000 - 5.000.000' ? 'selected' : '' }}>Rp 3.000.000 - 5.000.000 (3-5 juta)</option>
                                <option value="Rp 5.000.000 - 10.000.000" {{ old('penghasilan_per_bulan_ibu') == 'Rp 5.000.000 - 10.000.000' ? 'selected' : '' }}>Rp 5.000.000 - 10.000.000 (5-10 juta)</option>
                                <option value="> Rp 10.000.000" {{ old('penghasilan_per_bulan_ibu') == '> Rp 10.000.000' ? 'selected' : '' }}>> Rp 10.000.000 (> 10 juta)</option>
                            </select>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Nomor Telepon/WA Ibu <span class="text-red-500">*</span></label>
                            <input type="text" name="nomor_telepon_ibu" value="{{ old('nomor_telepon_ibu') }}" required 
                                   maxlength="15" minlength="10"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white numeric-only" 
                                   placeholder="081234567890">
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Email Ibu</label>
                            <input type="email" name="email_ibu" value="{{ old('email_ibu') }}" 
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" 
                                   placeholder="ibu@email.com">
                        </div>
                    </div>
                </section>
            </div>

            <!-- E. DATA WALI -->
            <section class="bg-white dark:bg-slate-800 rounded-2xl p-8 border border-slate-100 dark:border-slate-700 shadow-sm mb-8">
                <div class="flex items-center justify-between mb-8 border-b pb-4">
                    <div class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined">supervisor_account</span>
                        </div>
                        E. DATA WALI (Opsional)
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium text-slate-500">Punya Wali?</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="punya_wali" id="toggleWali" value="1" {{ old('punya_wali') ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                        </label>
                    </div>
                </div>

                <!-- Fields Wali (hidden by default) -->
                <div class="{{ old('punya_wali') ? '' : 'hidden' }} space-y-6" id="section_wali">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Nama Lengkap Wali</label>
                            <input type="text" name="nama_wali" value="{{ old('nama_wali') }}" 
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" 
                                   placeholder="Nama Lengkap Wali">
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">NIK Wali</label>
                            <input type="text" name="nik_wali" value="{{ old('nik_wali') }}" 
                                   maxlength="16" minlength="16"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white numeric-only" 
                                   placeholder="16 digit NIK">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Tempat Lahir Wali</label>
                                <input type="text" name="tempat_lahir_wali" value="{{ old('tempat_lahir_wali') }}" 
                                       class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" 
                                       placeholder="Tempat Lahir">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Tanggal Lahir Wali</label>
                                <input type="date" name="tanggal_lahir_wali" value="{{ old('tanggal_lahir_wali') }}" max="{{ date('Y-m-d') }}"
                                       class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white">
                            </div>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Nomor Telepon/WA Wali</label>
                            <input type="text" name="nomor_telepon_wali" value="{{ old('nomor_telepon_wali') }}" 
                                   maxlength="15" minlength="10"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white numeric-only" 
                                   placeholder="081234567890">
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Pendidikan Wali</label>
                            <select name="pendidikan_wali" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white">
                                <option value="">Pilih Pendidikan</option>
                                @foreach(['Tidak Sekolah', 'SD', 'SMP', 'SMA', 'D1', 'D2', 'D3', 'S1', 'S2', 'S3'] as $p)
                                    <option value="{{ $p }}" {{ old('pendidikan_wali') == $p ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Email Wali</label>
                            <input type="email" name="email_wali" value="{{ old('email_wali') }}" 
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white" 
                                   placeholder="wali@email.com">
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Hubungan dengan Anak <span class="text-red-500">*</span></label>
                            <select name="hubungan_dengan_anak" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white">
                                <option value="">Pilih Hubungan</option>
                                @foreach(['Kakek', 'Nenek', 'Bibi', 'Paman', 'Lainnya'] as $hub)
                                    <option value="{{ $hub }}" {{ old('hubungan_dengan_anak') == $hub ? 'selected' : '' }}>{{ $hub }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex flex-col gap-2 md:col-span-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Pekerjaan Wali</label>
                            <select name="pekerjaan_wali" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all outline-none dark:text-white">
                                <option value="">Pilih Pekerjaan</option>
                                @foreach(['Ibu Rumah Tangga', 'Pekerja Informal', 'Wirausaha', 'Pegawai Swasta', 'Pegawai Negeri Sipil (PNS)'] as $job)
                                    <option value="{{ $job }}" {{ old('pekerjaan_wali') == $job ? 'selected' : '' }}>{{ $job }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-end gap-4 pb-10">
                <a href="{{ route('admin.siswa.siswa-aktif.index') }}" 
                   class="px-8 py-3.5 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-2xl font-bold text-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition-all border border-slate-200 dark:border-slate-700">
                    Batal
                </a>
                <button type="submit" id="btnSubmit"
                        class="px-10 py-3.5 bg-primary text-white rounded-2xl font-bold text-sm hover:bg-primary/90 transition-all shadow-xl shadow-primary/30 flex items-center justify-center gap-2">
                    <span id="btnText">Simpan Data Siswa</span>
                    <div id="btnLoading" class="hidden">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ===== TOGGLE ALAMAT KK =====
        const checkAlamatKK = document.getElementById('alamat_kk_sama');
        const sectionAlamatKK = document.getElementById('section_alamat_kk');
        if (checkAlamatKK && sectionAlamatKK) {
            checkAlamatKK.addEventListener('change', function() {
                if (this.checked) {
                    sectionAlamatKK.classList.add('hidden');
                } else {
                    sectionAlamatKK.classList.remove('hidden');
                }
            });
        }

        // ===== TOGGLE WALI =====
        const toggleWali = document.getElementById('toggleWali');
        const sectionWali = document.getElementById('section_wali');
        if (toggleWali && sectionWali) {
            toggleWali.addEventListener('change', function() {
                if (this.checked) {
                    sectionWali.classList.remove('hidden');
                } else {
                    sectionWali.classList.add('hidden');
                }
            });
        }

        // ===== VALIDASI ANGKA (NUMERIC-ONLY) =====
        const numericInputs = document.querySelectorAll('.numeric-only');
        
        numericInputs.forEach(input => {
            // 1. CEK INPUT KEYPRESS (hanya angka)
            input.addEventListener('keypress', function(e) {
                // Allow: backspace, delete, tab, escape, enter
                if (e.key === 'Backspace' || e.key === 'Delete' || e.key === 'Tab' || e.key === 'Escape' || e.key === 'Enter') {
                    return;
                }
                
                // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                if (e.ctrlKey && (e.key === 'a' || e.key === 'c' || e.key === 'v' || e.key === 'x')) {
                    return;
                }
                
                // Pastikan hanya angka
                if (!/^[0-9]$/.test(e.key)) {
                    e.preventDefault();
                }
            });
            
            // 2. CEK INPUT (saat mengetik) - batasi panjang dan hapus non-angka
            input.addEventListener('input', function() {
                // Simpan posisi cursor
                const cursorPos = this.selectionStart;
                const oldLength = this.value.length;
                
                // Hapus semua karakter non-angka
                this.value = this.value.replace(/[^0-9]/g, '');
                
                // Batasi panjang sesuai maxlength
                const maxLength = this.getAttribute('maxlength');
                if (maxLength && this.value.length > parseInt(maxLength)) {
                    this.value = this.value.slice(0, maxLength);
                }
                
                // Kembalikan posisi cursor (kurangi jika karakter dihapus)
                const newLength = this.value.length;
                if (newLength < oldLength) {
                    this.setSelectionRange(cursorPos - (oldLength - newLength), cursorPos - (oldLength - newLength));
                }
            });
            
            // 3. CEK BLUR (saat input kehilangan fokus) - validasi minlength
            input.addEventListener('blur', function() {
                const minLength = this.getAttribute('minlength');
                const value = this.value;
                
                // Hapus pesan error lama jika ada
                const oldError = this.parentNode.querySelector('.numeric-error');
                if (oldError) oldError.remove();
                
                if (minLength && value.length > 0 && value.length < parseInt(minLength)) {
                    // Tandai error
                    this.classList.add('border-red-500');
                    
                    // Buat pesan error dinamis
                    const errorMsg = document.createElement('p');
                    errorMsg.className = 'text-xs text-red-500 mt-1 numeric-error';
                    errorMsg.textContent = `Harus ${minLength} digit angka`;
                    this.parentNode.appendChild(errorMsg);
                } else {
                    this.classList.remove('border-red-500');
                }
            });
            
            // 4. CEK PASTE (hanya angka)
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                
                // Ambil data clipboard
                const pasteData = (e.clipboardData || window.clipboardData).getData('text');
                
                // Hanya ambil angka dari paste
                const numericData = pasteData.replace(/[^0-9]/g, '');
                
                // Dapatkan posisi cursor
                const start = this.selectionStart;
                const end = this.selectionEnd;
                
                // Gabungkan dengan nilai yang sudah ada
                const currentValue = this.value;
                const newValue = currentValue.substring(0, start) + numericData + currentValue.substring(end);
                
                // Hapus non-angka dan batasi
                this.value = newValue.replace(/[^0-9]/g, '');
                
                // Batasi panjang
                const maxLength = this.getAttribute('maxlength');
                if (maxLength && this.value.length > parseInt(maxLength)) {
                    this.value = this.value.slice(0, maxLength);
                }
                
                // Trigger input event untuk validasi
                this.dispatchEvent(new Event('input'));
            });
        });

        // ===== FOTO PREVIEW =====
        const fotoInput = document.getElementById('foto-input');
        const fotoPreview = document.getElementById('foto-preview');
        const removeFoto = document.getElementById('remove-foto');

        if (fotoInput && fotoPreview) {
            fotoInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    // Cek ukuran file
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Ukuran file terlalu besar! Maksimal 2MB.');
                        this.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        fotoPreview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                        removeFoto.classList.remove('hidden');
                    }
                    reader.readAsDataURL(file);
                }
            });

            removeFoto.addEventListener('click', function() {
                fotoInput.value = '';
                fotoPreview.innerHTML = `<span class="material-symbols-outlined text-slate-400 text-3xl">add_a_photo</span>`;
                this.classList.add('hidden');
            });
        }

        // ===== SUBMIT LOADING =====
        const form = document.getElementById('siswaForm');
        const btnSubmit = document.getElementById('btnSubmit');
        const btnText = document.getElementById('btnText');
        const btnLoading = document.getElementById('btnLoading');

        if (form && btnSubmit) {
            form.addEventListener('submit', function(e) {
                // Biarkan validasi HTML5 berjalan dulu
                if (!form.checkValidity()) {
                    return;
                }

                // Cek validasi minlength numeric-only secara manual jika perlu
                const invalidNumerics = form.querySelectorAll('.numeric-only.border-red-500');
                if (invalidNumerics.length > 0) {
                    e.preventDefault();
                    invalidNumerics[0].focus();
                    alert('Beberapa field angka belum sesuai ketentuan format.');
                    return;
                }

                btnSubmit.disabled = true;
                btnSubmit.classList.add('opacity-80', 'cursor-not-allowed');
                btnText.textContent = 'Menyimpan...';
                btnLoading.classList.remove('hidden');
            });
        }
    });
</script>
@endpush

@push('styles')
<style>
    .form-input:focus {
        @apply ring-2 ring-primary/20 border-primary;
    }
    
    /* Sembunyikan spinner di input number */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
@endpush
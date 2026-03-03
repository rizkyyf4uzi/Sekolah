@extends('layouts.admin')

@section('title', 'Edit Guru & Staff')
@section('breadcrumb', 'Edit Guru')

@section('content')
<div class="max-w-5xl mx-auto pb-20">
    <!-- Header Page -->
    <div class="mb-10 text-center">
        <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight mb-3">Pembaruan Data <span class="text-primary">Guru</span></h1>
        <p class="text-sm font-bold text-slate-400 uppercase tracking-[0.2em] flex items-center justify-center gap-2">
            <span class="w-8 h-[2px] bg-primary rounded-full"></span>
            Perbarui informasi pendidik dan tenaga kependidikan
            <span class="w-8 h-[2px] bg-primary rounded-full"></span>
        </p>
    </div>

    <form action="{{ route('admin.guru.update', $guru->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')
        
        <!-- Section A: Identitas Pribadi -->
        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden border-b-4 border-b-primary/10">
            <div class="p-8 border-b border-slate-50 dark:border-slate-700/50 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">person_edit</span>
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight leading-none mb-1.5">Identitas Pribadi</h2>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Informasi dasar dan kependudukan</p>
                </div>
            </div>
            
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- NIP -->
                <div class="space-y-2">
                    <label for="nip" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">NIP (Opsional)</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400 group-focus-within:text-primary transition-colors text-lg">badge</span>
                        </div>
                        <input type="text" id="nip" name="nip" 
                               value="{{ old('nip', $guru->nip) }}"
                               class="w-full pl-12 pr-4 py-4 bg-slate-50 dark:bg-slate-900 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all text-sm font-bold text-slate-600 dark:text-slate-300 placeholder:text-slate-300"
                               placeholder="19800101XXXXXXXXXX">
                    </div>
                    @error('nip') <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest animate-pulse ml-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Nama -->
                <div class="space-y-2">
                    <label for="nama" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap *</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400 group-focus-within:text-primary transition-colors text-lg">person</span>
                        </div>
                        <input type="text" id="nama" name="nama" required
                               value="{{ old('nama', $guru->nama) }}"
                               class="w-full pl-12 pr-4 py-4 bg-slate-50 dark:bg-slate-900 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all text-sm font-bold text-slate-600 dark:text-slate-300 placeholder:text-slate-300"
                               placeholder="Contoh: Ahmad Subardjo, S.Pd.">
                    </div>
                    @error('nama') <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest animate-pulse ml-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Tempat Lahir -->
                <div class="space-y-2">
                    <label for="tempat_lahir" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tempat Lahir</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400 group-focus-within:text-primary transition-colors text-lg">location_on</span>
                        </div>
                        <input type="text" id="tempat_lahir" name="tempat_lahir"
                               value="{{ old('tempat_lahir', $guru->tempat_lahir) }}"
                               class="w-full pl-12 pr-4 py-4 bg-slate-50 dark:bg-slate-900 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all text-sm font-bold text-slate-600 dark:text-slate-300 placeholder:text-slate-300"
                               placeholder="Kota Kelahiran">
                    </div>
                </div>

                <!-- Tanggal Lahir -->
                <div class="space-y-2">
                    <label for="tanggal_lahir" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Lahir *</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400 group-focus-within:text-primary transition-colors text-lg">calendar_today</span>
                        </div>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" required
                               value="{{ old('tanggal_lahir', $guru->tanggal_lahir) }}"
                               class="w-full pl-12 pr-4 py-4 bg-slate-50 dark:bg-slate-900 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all text-sm font-bold text-slate-600 dark:text-slate-300 appearance-none">
                    </div>
                    @error('tanggal_lahir') <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest animate-pulse ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Jenis Kelamin -->
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Jenis Kelamin *</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="jenis_kelamin" value="L" required
                                   {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'L' ? 'checked' : '' }}
                                   class="peer hidden">
                            <div class="flex items-center justify-center gap-3 p-4 bg-slate-50 dark:bg-slate-900 border-2 border-transparent rounded-2xl peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:text-primary transition-all group-hover:bg-slate-100 dark:group-hover:bg-slate-700">
                                <span class="material-symbols-outlined text-xl">male</span>
                                <span class="text-[10px] font-black uppercase tracking-widest">Laki-laki</span>
                            </div>
                        </label>
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="jenis_kelamin" value="P"
                                   {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'P' ? 'checked' : '' }}
                                   class="peer hidden">
                            <div class="flex items-center justify-center gap-3 p-4 bg-slate-50 dark:bg-slate-900 border-2 border-transparent rounded-2xl peer-checked:border-pink-500 peer-checked:bg-pink-500/5 peer-checked:text-pink-600 transition-all group-hover:bg-slate-100 dark:group-hover:bg-slate-700">
                                <span class="material-symbols-outlined text-xl">female</span>
                                <span class="text-[10px] font-black uppercase tracking-widest">Perempuan</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Jenjang Pendidikan -->
                <div class="space-y-2">
                    <label for="pendidikan_terakhir" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pendidikan Terakhir</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400 group-focus-within:text-primary transition-colors text-lg">history_edu</span>
                        </div>
                        <select id="pendidikan_terakhir" name="pendidikan_terakhir"
                                class="w-full pl-12 pr-10 py-4 bg-slate-50 dark:bg-slate-900 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all text-sm font-bold text-slate-600 dark:text-slate-300 appearance-none cursor-pointer">
                            <option value="">Pilih Pendidikan</option>
                            @foreach(['SMA' => 'SMA/Sederajat', 'D1' => 'Diploma 1', 'D2' => 'Diploma 2', 'D3' => 'Diploma 3', 'D4' => 'Diploma 4', 'S1' => 'Sarjana (S1)', 'S2' => 'Magister (S2)', 'S3' => 'Doktor (S3)'] as $val => $label)
                                <option value="{{ $val }}" {{ old('pendidikan_terakhir', $guru->pendidikan_terakhir) == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-300">
                            <span class="material-symbols-outlined text-lg font-bold">expand_more</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section B: Kontak & Jabatan -->
        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden border-b-4 border-b-emerald-500/10">
            <div class="p-8 border-b border-slate-50 dark:border-slate-700/50 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">contact_mail</span>
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight leading-none mb-1.5">Kontak & Karir</h2>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Informasi komunikasi dan posisi kerja</p>
                </div>
            </div>
            
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- No HP -->
                <div class="space-y-2">
                    <label for="no_hp" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">No. HP / WhatsApp *</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400 group-focus-within:text-primary transition-colors text-lg">phone_android</span>
                        </div>
                        <input type="tel" id="no_hp" name="no_hp" required
                               value="{{ old('no_hp', $guru->no_hp) }}"
                               class="w-full pl-12 pr-4 py-4 bg-slate-50 dark:bg-slate-900 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all text-sm font-bold text-slate-600 dark:text-slate-300 placeholder:text-slate-300"
                               placeholder="08XXXXXXXXXX">
                    </div>
                    @error('no_hp') <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest animate-pulse ml-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Email -->
                <div class="space-y-2">
                    <label for="email" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Alamat Email *</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400 group-focus-within:text-primary transition-colors text-lg">alternate_email</span>
                        </div>
                        <input type="email" id="email" name="email" required
                               value="{{ old('email', $guru->email) }}"
                               class="w-full pl-12 pr-4 py-4 bg-slate-50 dark:bg-slate-900 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all text-sm font-bold text-slate-600 dark:text-slate-300 placeholder:text-slate-300"
                               placeholder="nama@email.com">
                    </div>
                    @error('email') <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest animate-pulse ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Jabatan -->
                <div class="space-y-2">
                    <label for="jabatan" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Jabatan Struktural *</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400 group-focus-within:text-primary transition-colors text-lg">work</span>
                        </div>
                        <select id="jabatan" name="jabatan" required
                                class="w-full pl-12 pr-10 py-4 bg-slate-50 dark:bg-slate-900 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all text-sm font-bold text-slate-600 dark:text-slate-300 appearance-none cursor-pointer">
                            <option value="">Pilih Jabatan</option>
                            <option value="guru" {{ old('jabatan', $guru->jabatan) == 'guru' ? 'selected' : '' }}>Guru Pengajar</option>
                            <option value="staff" {{ old('jabatan', $guru->jabatan) == 'staff' ? 'selected' : '' }}>Staff Administrasi</option>
                        </select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-300">
                            <span class="material-symbols-outlined text-lg font-bold">expand_more</span>
                        </div>
                    </div>
                    @error('jabatan') <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest animate-pulse ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Kelompok (Dinamis) -->
                <div id="kelompok-container" class="space-y-2 hidden">
                    <label for="kelompok" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Wali Kelompok *</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400 group-focus-within:text-primary transition-colors text-lg">groups</span>
                        </div>
                        <select id="kelompok" name="kelompok"
                                class="w-full pl-12 pr-10 py-4 bg-slate-50 dark:bg-slate-900 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all text-sm font-bold text-slate-600 dark:text-slate-300 appearance-none cursor-pointer">
                            <option value="">Pilih Kelompok</option>
                            <option value="A" {{ old('kelompok', $guru->kelompok) == 'A' ? 'selected' : '' }}>Kelompok A (Usia 4-5 th)</option>
                            <option value="B" {{ old('kelompok', $guru->kelompok) == 'B' ? 'selected' : '' }}>Kelompok B (Usia 5-6 th)</option>
                        </select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-300">
                            <span class="material-symbols-outlined text-lg font-bold">expand_more</span>
                        </div>
                    </div>
                    @error('kelompok') <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest animate-pulse ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Alamat (Full Width) -->
                <div class="space-y-2 md:col-span-2">
                    <label for="alamat" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 text-primary">Alamat Tempat Tinggal Saat Ini *</label>
                    <div class="relative group">
                        <div class="absolute top-4 left-4 pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400 group-focus-within:text-primary transition-colors text-lg">home</span>
                        </div>
                        <textarea id="alamat" name="alamat" rows="3" required
                                  class="w-full pl-12 pr-4 py-4 bg-slate-50 dark:bg-slate-900 border-transparent rounded-[1.5rem] focus:bg-white dark:focus:bg-slate-800 focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all text-sm font-bold text-slate-600 dark:text-slate-300 placeholder:text-slate-300 resize-none"
                                  placeholder="Tuliskan alamat lengkap sesuai KTP atau domisili...">{{ old('alamat', $guru->alamat) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section C: Foto Media -->
        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden border-b-4 border-b-amber-500/10">
            <div class="p-8 border-b border-slate-50 dark:border-slate-700/50 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">photo_camera</span>
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight leading-none mb-1.5">Foto Profil</h2>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Format formal untuk kartu identitas</p>
                </div>
            </div>
            
            <div class="p-8">
                <div class="flex flex-col md:flex-row gap-8 items-start">
                    <div class="w-full md:w-1/3">
                        <label class="group relative flex flex-col items-center justify-center w-full aspect-square bg-slate-50 dark:bg-slate-900 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-[2rem] hover:bg-slate-100/50 dark:hover:bg-slate-800/50 hover:border-primary/50 transition-all cursor-pointer overflow-hidden">
                            <input type="file" id="foto" name="foto" accept="image/*" class="hidden">
                            
                            <div id="upload-placeholder" class="flex flex-col items-center text-center p-6 transition-all {{ $guru->foto ? 'hidden' : '' }}">
                                <div class="w-16 h-16 rounded-2xl bg-white dark:bg-slate-800 flex items-center justify-center shadow-sm text-slate-400 group-hover:text-primary transition-colors mb-4">
                                    <span class="material-symbols-outlined text-3xl">add_a_photo</span>
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 group-hover:text-slate-600 transition-colors">Pilih Berkas</span>
                                <span class="text-[8px] font-bold text-slate-300 uppercase tracking-widest mt-2">JPG, PNG, JPEG (MAX 2MB)</span>
                            </div>

                            <img id="preview-image" 
                                 src="{{ $guru->foto ? asset('storage/' . $guru->foto) : '' }}"
                                 class="absolute inset-0 w-full h-full object-cover {{ $guru->foto ? '' : 'hidden' }} transition-all duration-300">
                            
                            <div id="change-overlay" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm items-center justify-center hidden group-hover:flex transition-all">
                                <span class="text-white text-[10px] font-black uppercase tracking-widest">Ganti Foto</span>
                            </div>
                        </label>
                        @error('foto') <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest animate-pulse mt-3 text-center">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex-1 space-y-4">
                        <div class="p-6 rounded-3xl bg-amber-50/50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-900/20 text-center md:text-left">
                            <h4 class="text-xs font-black text-amber-700 dark:text-amber-500 uppercase tracking-widest mb-2 flex items-center justify-center md:justify-start gap-2">
                                <span class="material-symbols-outlined text-lg font-bold">info</span>
                                Status Foto
                            </h4>
                            <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                                @if($guru->foto)
                                    Guru saat ini memiliki foto profil aktif. Mengunggah berkas baru akan menggantikan foto lama secara permanen.
                                @else
                                    Guru belum memiliki foto profil. Harap unggah foto formal terbaru.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col sm:flex-row items-center justify-end gap-4 p-4">
            <a href="{{ route('admin.guru.index') }}" 
               class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-black text-xs uppercase tracking-widest border border-slate-200 dark:border-slate-600 shadow-sm hover:bg-slate-50 transition-all text-center">
                Batal
            </a>
            <button type="submit" 
                    id="submit-btn"
                    class="w-full sm:w-auto px-10 py-4 rounded-2xl bg-primary text-white font-black text-xs uppercase tracking-widest shadow-xl shadow-primary/20 hover:scale-105 transition-all active:scale-95 flex items-center justify-center gap-3">
                <span class="material-symbols-outlined text-lg">update</span>
                Perbarui Data Guru
            </button>
        </div>
    </form>
</div>

<!-- Loading Overlay -->
<div id="loading-spinner" class="fixed inset-0 bg-slate-900/20 backdrop-blur-[2px] z-[60] flex items-center justify-center hidden opacity-0 transition-all duration-300">
    <div class="bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] shadow-2xl flex flex-col items-center gap-4">
        <div class="w-16 h-16 border-4 border-primary/10 border-t-primary rounded-full animate-spin"></div>
        <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Memproses Perubahan...</span>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Foto Preview Logic
    const fotoInput = document.getElementById('foto');
    const previewImg = document.getElementById('preview-image');
    const placeholder = document.getElementById('upload-placeholder');
    const overlay = document.getElementById('change-overlay');
    
    if (fotoInput) {
        fotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    overlay.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Kelompok Visibility Logic
    const jabatanSelect = document.getElementById('jabatan');
    const kelompokContainer = document.getElementById('kelompok-container');
    const kelompokSelect = document.getElementById('kelompok');
    
    function toggleKelompok() {
        if (jabatanSelect.value === 'guru') {
            kelompokContainer.classList.remove('hidden');
            kelompokSelect.required = true;
        } else {
            kelompokContainer.classList.add('hidden');
            kelompokSelect.required = false;
            // Note: Don't clear value here on Edit page unless user explicitly changes position
            // But if it's hidden, it won't be required.
        }
    }
    
    jabatanSelect.addEventListener('change', toggleKelompok);
    toggleKelompok(); // Initial check

    // Date constraints
    const tanggalLahir = document.getElementById('tanggal_lahir');
    if (tanggalLahir) {
        const today = new Date();
        const minDate = new Date(today.getFullYear() - 65, today.getMonth(), today.getDate());
        const maxDate = new Date(today.getFullYear() - 17, today.getMonth(), today.getDate());
        tanggalLahir.max = maxDate.toISOString().split('T')[0];
        tanggalLahir.min = minDate.toISOString().split('T')[0];
    }

    // Loader logic
    const form = document.querySelector('form');
    const spinner = document.getElementById('loading-spinner');
    
    form.addEventListener('submit', function() {
        spinner.classList.remove('hidden');
        setTimeout(() => spinner.classList.add('opacity-100'), 10);
        document.getElementById('submit-btn').disabled = true;
    });
});
</script>
@endpush

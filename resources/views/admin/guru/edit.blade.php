@extends('layouts.admin')

@section('title', 'Edit Data Guru - SchoolAdmin&nbsp;Pro')

@section('content')
<div class="min-h-screen bg-background-light dark:bg-background-dark p-8 pb-8">
    <div class="max-w-7xl mx-auto">
        <!-- Breadcrumb -->
        <nav aria-label="Breadcrumb" class="flex mb-6">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <span class="text-slate-400 text-sm font-medium">Master Data</span>
                </li>
                <li>
                    <div class="flex items-center">
                        <span class="material-symbols-outlined text-slate-400 text-sm mx-1">chevron_right</span>
                        <span class="text-slate-400 text-sm font-medium">Data Guru</span>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <span class="material-symbols-outlined text-slate-400 text-sm mx-1">chevron_right</span>
                        <span class="text-slate-800 text-sm font-bold">Edit Data Guru</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Edit Data Guru</h1>
                <p class="text-sm text-slate-500 mt-1">Silakan lengkapi formulir di bawah ini untuk mengubah data guru atau staf.</p>
            </div>
            <a href="{{ route('admin.guru.index') }}" class="flex items-center gap-2 text-primary font-bold text-sm hover:underline">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                Kembali ke Daftar
            </a>
        </div>

        <form action="{{ route('admin.guru.update', $guru->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Data Pribadi -->
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-primary/10 text-primary rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined">person</span>
                    </div>
                    <h2 class="text-lg font-bold text-slate-800">Data Pribadi</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="nama" class="text-sm font-bold text-slate-700">Nama Lengkap *</label>
                        <input type="text" id="nama" name="nama" required value="{{ old('nama', $guru->nama) }}"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all"
                               placeholder="Masukkan nama lengkap beserta gelar">
                        @error('nama') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="nip" class="text-sm font-bold text-slate-700">NIP</label>
                        <input type="text" id="nip" name="nip" value="{{ old('nip', $guru->nip) }}" maxlength="18"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all"
                               placeholder="Masukkan Nomor Induk Pegawai">
                        @error('nip') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="tempat_lahir" class="text-sm font-bold text-slate-700">Tempat Lahir</label>
                        <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $guru->tempat_lahir) }}"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all"
                               placeholder="Kota Kelahiran">
                    </div>
                    <div class="space-y-2">
                        <label for="tanggal_lahir" class="text-sm font-bold text-slate-700">Tanggal Lahir *</label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" required value="{{ old('tanggal_lahir', $guru->tanggal_lahir) }}"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all">
                        @error('tanggal_lahir') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 block">Jenis Kelamin *</label>
                        <div class="flex items-center gap-6 h-[42px]">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="jenis_kelamin" value="L" required
                                       {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'L' ? 'checked' : '' }}
                                       class="w-4 h-4 text-primary border-slate-300 focus:ring-primary/20">
                                <span class="text-sm text-slate-600 group-hover:text-slate-900 transition-colors">Laki-laki</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="jenis_kelamin" value="P"
                                       {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'P' ? 'checked' : '' }}
                                       class="w-4 h-4 text-primary border-slate-300 focus:ring-primary/20">
                                <span class="text-sm text-slate-600 group-hover:text-slate-900 transition-colors">Perempuan</span>
                            </label>
                        </div>
                        @error('jenis_kelamin') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="agama" class="text-sm font-bold text-slate-700">Agama</label>
                        <select id="agama" name="agama"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all">
                            <option value="">Pilih Agama</option>
                            @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Khonghucu'] as $agama)
                                <option value="{{ $agama }}" {{ old('agama', $guru->agama) == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label for="no_hp" class="text-sm font-bold text-slate-700">No HP *</label>
                        <input type="tel" id="no_hp" name="no_hp" required value="{{ old('no_hp', $guru->no_hp) }}" maxlength="13"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all"
                               placeholder="Contoh: 081234567890">
                        @error('no_hp') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="email" class="text-sm font-bold text-slate-700">Email *</label>
                        <input type="email" id="email" name="email" required value="{{ old('email', $guru->email) }}"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all"
                               placeholder="nama@sekolah.sch.id">
                        @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Data Kepegawaian -->
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-primary/10 text-primary rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined">work</span>
                    </div>
                    <h2 class="text-lg font-bold text-slate-800">Data Kepegawaian</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="jabatan" class="text-sm font-bold text-slate-700">Jabatan *</label>
                        <select id="jabatan" name="jabatan" required
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all">
                            <option value="">Pilih Jabatan</option>
                            <option value="guru" {{ old('jabatan', $guru->jabatan) == 'guru' ? 'selected' : '' }}>Guru</option>
                            <option value="staff" {{ old('jabatan', $guru->jabatan) == 'staff' ? 'selected' : '' }}>Staf</option>
                        </select>
                        @error('jabatan') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div id="kelompok-container" class="space-y-2 {{ old('jabatan', $guru->jabatan) == 'guru' ? '' : 'hidden' }}">
                        <label for="kelompok" class="text-sm font-bold text-slate-700">Kelompok</label>
                        <select id="kelompok" name="kelompok"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all">
                            <option value="">Pilih Kelompok</option>
                            <option value="A" {{ old('kelompok', $guru->kelompok) == 'A' ? 'selected' : '' }}>Kelompok A</option>
                            <option value="B" {{ old('kelompok', $guru->kelompok) == 'B' ? 'selected' : '' }}>Kelompok B</option>
                        </select>
                        @error('kelompok') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="pendidikan_terakhir" class="text-sm font-bold text-slate-700">Pendidikan Terakhir</label>
                        <select id="pendidikan_terakhir" name="pendidikan_terakhir"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all">
                            <option value="">Pilih Pendidikan</option>
                            @foreach(['SMA' => 'SMA/Sederajat', 'D1' => 'D1', 'D2' => 'D2', 'D3' => 'D3', 'S1' => 'S1', 'S2' => 'S2', 'S3' => 'S3'] as $val => $label)
                                <option value="{{ $val }}" {{ old('pendidikan_terakhir', $guru->pendidikan_terakhir) == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Alamat -->
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-primary/10 text-primary rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined">home</span>
                    </div>
                    <h2 class="text-lg font-bold text-slate-800">Alamat</h2>
                </div>
                <div class="space-y-2">
                    <label for="alamat" class="text-sm font-bold text-slate-700">Alamat Lengkap *</label>
                    <textarea id="alamat" name="alamat" rows="4" required
                              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all resize-none"
                              placeholder="Masukkan alamat lengkap rumah tinggal saat ini">{{ old('alamat', $guru->alamat) }}</textarea>
                    @error('alamat') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-4 py-4">
                <a href="{{ route('admin.guru.index') }}"
                   class="px-8 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all">
                    Batal
                </a>
                <button type="submit"
                        class="px-10 py-3 bg-primary text-white rounded-xl font-bold text-sm hover:opacity-90 transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">save</span>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // NIP hanya angka dan max 18 karakter
    const nipInput = document.getElementById('nip');
    if (nipInput) {
        nipInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 18) {
                this.value = this.value.slice(0, 18);
            }
        });
    }

    // No HP hanya angka dan max 13 karakter
    const noHpInput = document.getElementById('no_hp');
    if (noHpInput) {
        noHpInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 13) {
                this.value = this.value.slice(0, 13);
            }
        });
    }

    // Kelompok toggle
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
        }
    }

    if (jabatanSelect) {
        jabatanSelect.addEventListener('change', toggleKelompok);
        toggleKelompok();
    }

    // Date constraints
    const tanggalLahir = document.getElementById('tanggal_lahir');
    if (tanggalLahir) {
        const today = new Date();
        tanggalLahir.max = new Date(today.getFullYear() - 17, today.getMonth(), today.getDate()).toISOString().split('T')[0];
        tanggalLahir.min = new Date(today.getFullYear() - 65, today.getMonth(), today.getDate()).toISOString().split('T')[0];
    }
});
</script>
@endpush

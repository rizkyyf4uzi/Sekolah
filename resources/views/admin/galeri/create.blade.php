{{-- resources/views/admin/galeri/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Tambah Album Galeri')

@section('content')
{{-- novalidate: matikan validasi native browser agar hidden file input tidak diblok --}}
<form action="{{ route('admin.galeri.store') }}"
      method="POST"
      enctype="multipart/form-data"
      id="formGaleri"
      novalidate>
    @csrf

    {{-- Sub-header: Back + Judul --}}
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.galeri.index') }}"
           class="flex items-center gap-2 text-slate-500 hover:text-primary transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
            <span class="text-sm font-medium">Kembali ke Galeri</span>
        </a>
        <div class="h-4 w-[1px] bg-slate-200"></div>
        <h1 class="text-lg font-bold text-slate-800">Tambah Album Baru</h1>
    </div>

    <div class="max-w-5xl mx-auto space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ===== Kolom Kiri (2/3) ===== --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Informasi Album --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                    <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">info</span>
                        Informasi Album
                    </h2>

                    <div class="space-y-5">
                        {{-- Judul --}}
                        <div>
                            <label for="judul" class="block text-sm font-semibold text-slate-700 mb-2">
                                Judul Album <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="judul"
                                   name="judul"
                                   value="{{ old('judul') }}"
                                   placeholder="Masukkan judul album kegiatan..."
                                   required
                                   class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 text-sm transition-all @error('judul') ring-2 ring-red-400 @enderror">
                            @error('judul')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kategori + Tanggal row --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            {{-- Kategori --}}
                            <div>
                                <label for="kategori" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Kategori <span class="text-red-500">*</span>
                                </label>
                                <select name="kategori"
                                        id="kategori"
                                        required
                                        class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 text-sm transition-all @error('kategori') ring-2 ring-red-400 @enderror">
                                    <option value="">Pilih Kategori</option>
                                    <option value="kegiatan"  {{ old('kategori') == 'kegiatan'  ? 'selected' : '' }}>Kegiatan</option>
                                    <option value="prestasi"  {{ old('kategori') == 'prestasi'  ? 'selected' : '' }}>Prestasi</option>
                                    <option value="fasilitas" {{ old('kategori') == 'fasilitas' ? 'selected' : '' }}>Fasilitas</option>
                                    <option value="acara"     {{ old('kategori') == 'acara'     ? 'selected' : '' }}>Acara Khusus</option>
                                    <option value="harian"    {{ old('kategori') == 'harian'    ? 'selected' : '' }}>Kegiatan Harian</option>
                                </select>
                                @error('kategori')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Tanggal --}}
                            <div>
                                <label for="tanggal" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Tanggal Kegiatan <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none">calendar_today</span>
                                    <input type="date"
                                           id="tanggal"
                                           name="tanggal"
                                           value="{{ old('tanggal', date('Y-m-d')) }}"
                                           required
                                           class="w-full pl-10 pr-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 text-sm transition-all @error('tanggal') ring-2 ring-red-400 @enderror">
                                </div>
                                @error('tanggal')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Lokasi --}}
                        <div>
                            <label for="lokasi" class="block text-sm font-semibold text-slate-700 mb-2">Lokasi</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none">location_on</span>
                                <input type="text"
                                       id="lokasi"
                                       name="lokasi"
                                       value="{{ old('lokasi', 'TK Harapan Bangsa 1') }}"
                                       placeholder="Tempat kegiatan"
                                       class="w-full pl-10 pr-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 text-sm transition-all @error('lokasi') ring-2 ring-red-400 @enderror">
                            </div>
                            @error('lokasi')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div>
                            <label for="deskripsi" class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Album</label>
                            <textarea name="deskripsi"
                                      id="deskripsi"
                                      rows="4"
                                      placeholder="Berikan deskripsi singkat mengenai kegiatan ini..."
                                      class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 text-sm transition-all resize-none @error('deskripsi') ring-2 ring-red-400 @enderror">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Upload File --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">cloud_upload</span>
                            Unggah Gambar
                        </h2>
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Max 5MB / file · Max 10 file</span>
                    </div>

                    {{-- Hidden Input di LUAR dropzone agar tidak ikut click handler --}}
                    <input type="file"
                           id="gambar"
                           name="gambar[]"
                           class="hidden"
                           multiple
                           accept="image/jpeg,image/png,image/jpg,image/gif">

                    {{-- Dropzone --}}
                    <div id="dropzone"
                         class="border-2 border-dashed border-slate-200 rounded-2xl p-10 flex flex-col items-center justify-center bg-slate-50/50 hover:bg-lavender/10 hover:border-primary/30 transition-all group cursor-pointer">
                        <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center shadow-sm mb-4 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-primary text-3xl">upload_file</span>
                        </div>
                        <p class="text-slate-700 font-bold">Tarik dan lepas file di sini</p>
                        <p class="text-slate-400 text-sm mt-1">atau klik untuk memilih file dari komputer</p>
                        <p class="text-[11px] text-slate-400 mt-3">Format: JPG, JPEG, PNG, GIF &nbsp;·&nbsp; Minimal 1, Maksimal 10 gambar</p>
                    </div>

                    @error('gambar')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                    @error('gambar.*')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror

                    {{-- File Counter + Clear --}}
                    <div id="previewSection" class="mt-6 space-y-4">
                        <div id="fileCounter" class="hidden">
                            <div class="flex items-center justify-between p-3 bg-lavender/30 border border-lavender rounded-xl">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary text-lg">photo_library</span>
                                    <span class="text-sm font-bold text-primary">
                                        <span id="fileCount">0</span> gambar dipilih
                                    </span>
                                </div>
                                <button type="button"
                                        id="clearAllBtn"
                                        class="text-xs font-bold text-red-500 hover:text-red-700 flex items-center gap-1 transition-colors">
                                    <span class="material-symbols-outlined text-sm">delete_sweep</span>
                                    Hapus semua
                                </button>
                            </div>
                        </div>

                        {{-- Preview Grid --}}
                        <div id="imagePreviews" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4"></div>
                    </div>
                </div>
            </div>

            {{-- ===== Kolom Kanan (1/3) ===== --}}
            <div class="space-y-6">

                {{-- Pengaturan Album --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                    <h2 class="text-sm font-bold text-slate-800 mb-4 uppercase tracking-widest text-center">Pengaturan Album</h2>

                    {{-- Toggle Publish --}}
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                        <div>
                            <p class="text-sm font-bold text-slate-700">Status Visibilitas</p>
                            <p class="text-[10px] text-slate-400 uppercase font-medium">Publik atau Draft</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox"
                                   name="is_published"
                                   value="1"
                                   id="togglePublish"
                                   class="sr-only"
                                   {{ old('is_published', true) ? 'checked' : '' }}>
                            <div id="toggleBg" class="w-11 h-5 rounded-full transition-colors duration-200 flex items-center bg-slate-200">
                                <div id="toggleDot" class="bg-white w-4 h-4 rounded-full shadow-sm transform transition-transform duration-200 ml-0.5"></div>
                            </div>
                            <span id="publishLabel" class="ml-3 text-xs font-bold text-primary">Public</span>
                        </label>
                    </div>
                </div>

                {{-- Thumbnail --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                    <h2 class="text-sm font-bold text-slate-800 mb-4 uppercase tracking-widest text-center">Thumbnail Album</h2>
                    <div id="thumbnailZone"
                         class="aspect-video rounded-xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center bg-slate-50 hover:bg-lavender/10 transition-all cursor-pointer overflow-hidden group">
                        <div id="thumbnailPlaceholder" class="flex flex-col items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400 text-3xl mb-2">add_photo_alternate</span>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center px-4">Pilih Cover Album</p>
                        </div>
                        <div id="thumbnailPreview" class="hidden w-full h-full relative">
                            <img id="thumbnailImg" alt="Cover preview" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="bg-white text-slate-800 px-3 py-1.5 rounded-lg text-xs font-bold">Ganti Foto</span>
                            </div>
                        </div>
                    </div>
                    <input type="file" id="thumbnailInput" class="hidden" accept="image/*">
                    <p class="text-[10px] text-slate-400 mt-3 italic text-center">Rekomendasi ukuran: 1280 x 720 px (16:9)</p>
                </div>

                {{-- Tips --}}
                <div class="bg-lavender/30 rounded-2xl p-5 border border-lavender">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="material-symbols-outlined text-primary">lightbulb</span>
                        <p class="text-sm font-bold text-primary">Tips</p>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Pastikan gambar cover menarik untuk audiens publik. Gunakan format .jpg atau .png untuk hasil terbaik. Minimal 1 gambar harus diupload sebelum menyimpan.
                    </p>
                </div>

                {{-- Action Buttons (Sticky on mobile, inline on desktop) --}}
                <div class="fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-slate-200 z-50 lg:relative lg:p-0 lg:bg-transparent lg:border-none lg:z-auto">
                    <div class="bg-white lg:rounded-2xl lg:p-5 lg:shadow-sm lg:border lg:border-slate-100 flex flex-col sm:flex-row lg:flex-col gap-3 max-w-5xl mx-auto">
                        <button type="submit"
                                id="btnSubmit"
                                class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 disabled:opacity-50 disabled:cursor-not-allowed order-1 sm:order-2 lg:order-1">
                            <span class="material-symbols-outlined text-lg">save</span>
                            Simpan Album
                        </button>
                        <a href="{{ route('admin.galeri.index') }}"
                           class="w-full flex items-center justify-center px-6 py-3 lg:py-2.5 text-sm font-bold text-slate-500 hover:text-primary border border-slate-200 rounded-xl bg-slate-50 hover:bg-lavender transition-all order-2 sm:order-1 lg:order-2">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Spacer for mobile sticky footer so content doesn't get hidden behind it --}}
        <div class="h-24 lg:hidden"></div>
    </div>
</form>
@endsection

@push('styles')
<style>
    .preview-item {
        animation: fadeIn 0.3s ease;
        position: relative;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .remove-btn {
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    .preview-item:hover .remove-btn {
        opacity: 1;
    }
    .image-container {
        position: relative;
        padding-bottom: 100%;
        overflow: hidden;
        border-radius: 0.75rem;
    }
    .image-container img {
        position: absolute;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ========== ELEMENTS ==========
    const imageInput       = document.getElementById('gambar');
    const dropzone         = document.getElementById('dropzone');
    const previewContainer = document.getElementById('imagePreviews');
    const fileCounter      = document.getElementById('fileCounter');
    const fileCountSpan    = document.getElementById('fileCount');
    const clearAllBtn      = document.getElementById('clearAllBtn');
    const btnSubmit        = document.getElementById('btnSubmit');
    const form             = document.getElementById('formGaleri');
    const togglePublish    = document.getElementById('togglePublish');
    const publishLabel     = document.getElementById('publishLabel');
    const toggleBg         = document.getElementById('toggleBg');
    const toggleDot        = document.getElementById('toggleDot');
    const thumbnailZone    = document.getElementById('thumbnailZone');
    const thumbnailInput   = document.getElementById('thumbnailInput');
    const thumbnailPreview = document.getElementById('thumbnailPreview');
    const thumbnailPlaceholder = document.getElementById('thumbnailPlaceholder');
    const thumbnailImg     = document.getElementById('thumbnailImg');

    // ========== CONSTANTS ==========
    const MAX_FILES     = 10;
    const MAX_SIZE      = 5 * 1024 * 1024; // 5MB
    const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];

    // ========== STATE ==========
    let selectedFiles = [];

    // ========== TOGGLE PUBLISH ==========
    function syncToggle() {
        if (togglePublish.checked) {
            toggleBg.classList.remove('bg-slate-200');
            toggleBg.classList.add('bg-primary');
            toggleDot.style.transform = 'translateX(1.5rem)';
            publishLabel.textContent = 'Public';
            publishLabel.className = 'ml-3 text-xs font-bold text-primary';
        } else {
            toggleBg.classList.remove('bg-primary');
            toggleBg.classList.add('bg-slate-200');
            toggleDot.style.transform = 'translateX(0)';
            publishLabel.textContent = 'Draft';
            publishLabel.className = 'ml-3 text-xs font-bold text-slate-400';
        }
    }
    togglePublish.addEventListener('change', syncToggle);
    syncToggle();

    // ========== THUMBNAIL ==========
    thumbnailZone.addEventListener('click', function () {
        thumbnailInput.click();
    });
    thumbnailInput.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            thumbnailImg.src = e.target.result;
            thumbnailPlaceholder.classList.add('hidden');
            thumbnailPreview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    });

    // ========== DROPZONE ==========
    dropzone.addEventListener('click', function (e) {
        if (e.target.tagName !== 'INPUT') imageInput.click();
    });
    dropzone.addEventListener('dragover', function (e) {
        e.preventDefault();
        this.classList.add('border-primary/50', 'bg-lavender/20');
    });
    dropzone.addEventListener('dragleave', function (e) {
        e.preventDefault();
        this.classList.remove('border-primary/50', 'bg-lavender/20');
    });
    dropzone.addEventListener('drop', function (e) {
        e.preventDefault();
        this.classList.remove('border-primary/50', 'bg-lavender/20');
        const files = Array.from(e.dataTransfer.files).filter(f => ALLOWED_TYPES.includes(f.type));
        if (files.length > 0) handleFiles(files);
        else alert('Hanya file gambar yang diperbolehkan!');
    });
    imageInput.addEventListener('change', function () {
        handleFiles(Array.from(this.files));
        this.value = '';
    });
    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', function () {
            selectedFiles = [];
            updatePreview();
        });
    }

    // ========== FORM SUBMIT via fetch+FormData ==========
    // Tidak pakai native submit karena DataTransfer→input.files
    // tidak reliable di semua browser saat form di-POST.
    // Files diambil langsung dari array JS selectedFiles.
    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        if (selectedFiles.length === 0) {
            alert('Minimal 1 gambar harus diupload!');
            return;
        }

        const originalLabel = btnSubmit.innerHTML;
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = `
            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Menyimpan...
        `;

        // Bangun FormData dari semua field teks di form
        const formData = new FormData(form);
        // Hapus entry gambar[] dari input native (kosong)
        formData.delete('gambar[]');
        // Append file langsung dari JS array (pasti terkirim)
        selectedFiles.forEach(function(file) {
            formData.append('gambar[]', file, file.name);
        });

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Sukses — redirect ke halaman index
                window.location.href = data.redirect;
            } else if (response.status === 422 && data.errors) {
                // Error validasi Laravel
                const msgs = Object.values(data.errors).flat().join('\n');
                alert('Validasi gagal:\n' + msgs);
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = originalLabel;
            } else {
                // Error server
                alert(data.message || 'Terjadi kesalahan. Silakan coba lagi.');
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = originalLabel;
            }
        } catch (err) {
            console.error('Upload error:', err);
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = originalLabel;
            alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
        }
    });

    // ========== FUNCTIONS ==========
    function handleFiles(files) {
        if (selectedFiles.length + files.length > MAX_FILES) {
            alert(`Maksimal ${MAX_FILES} file yang dapat diupload!`);
            return;
        }
        const validFiles = files.filter(file => {
            if (!ALLOWED_TYPES.includes(file.type)) { alert(`File "${file.name}" bukan gambar!`); return false; }
            if (file.size > MAX_SIZE)               { alert(`File "${file.name}" melebihi 5MB!`); return false; }
            if (selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                alert(`File "${file.name}" sudah dipilih!`); return false;
            }
            return true;
        });
        selectedFiles = [...selectedFiles, ...validFiles];
        updatePreview();
    }

    function updatePreview() {
        if (selectedFiles.length > 0) {
            fileCounter.classList.remove('hidden');
            fileCountSpan.textContent = selectedFiles.length;
        } else {
            fileCounter.classList.add('hidden');
        }

        previewContainer.innerHTML = '';

        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const item = document.createElement('div');
                item.className = 'preview-item';
                item.setAttribute('data-index', index);
                item.innerHTML = `
                    <div class="image-container border-2 border-slate-100">
                        <img src="${e.target.result}" alt="${file.name}" loading="lazy">
                        <button type="button"
                                onclick="removeFile(${index})"
                                class="remove-btn absolute top-1 right-1 p-1 bg-red-500 text-white rounded-full hover:bg-red-600 transition-all z-10">
                            <span class="material-symbols-outlined text-xs leading-none" style="font-size:14px">close</span>
                        </button>
                        <span class="absolute top-1 left-1 bg-primary text-white text-[10px] px-1.5 py-0.5 rounded-full font-bold">${index + 1}</span>
                        <span class="absolute bottom-1 right-1 bg-black/60 text-white text-[10px] px-1.5 py-0.5 rounded-full">${formatFileSize(file.size)}</span>
                    </div>
                    <p class="mt-1 text-[10px] text-slate-500 truncate px-1" title="${file.name}">
                        ${file.name.length > 20 ? file.name.substring(0, 17) + '...' : file.name}
                    </p>
                `;
                previewContainer.appendChild(item);
            };
            reader.readAsDataURL(file);
        });

    }

    function formatFileSize(bytes) {
        if (bytes < 1024)        return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    }

    window.removeFile = function (index) {
        selectedFiles.splice(index, 1);
        updatePreview();
    };
});
</script>
@endpush
{{-- resources/views/admin/galeri/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Galeri')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">
            Edit Galeri: {{ $galeri->judul }}
        </h2>
    </div>
    
    <div class="p-6">
        <form action="{{ route('admin.galeri.update', $galeri) }}" 
              method="POST" 
              enctype="multipart/form-data"
              id="formGaleri">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                {{-- Judul --}}
                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Galeri <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="judul"
                           name="judul" 
                           value="{{ old('judul', $galeri->judul) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('judul') border-red-500 @enderror"
                           placeholder="Masukkan judul galeri"
                           required>
                    @error('judul')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Kategori --}}
                <div>
                    <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="kategori" 
                            id="kategori"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('kategori') border-red-500 @enderror"
                            required>
                        <option value="">Pilih Kategori</option>
                        <option value="kegiatan" {{ old('kategori', $galeri->kategori) == 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                        <option value="prestasi" {{ old('kategori', $galeri->kategori) == 'prestasi' ? 'selected' : '' }}>Prestasi</option>
                        <option value="fasilitas" {{ old('kategori', $galeri->kategori) == 'fasilitas' ? 'selected' : '' }}>Fasilitas</option>
                        <option value="acara" {{ old('kategori', $galeri->kategori) == 'acara' ? 'selected' : '' }}>Acara Khusus</option>
                        <option value="harian" {{ old('kategori', $galeri->kategori) == 'harian' ? 'selected' : '' }}>Kegiatan Harian</option>
                    </select>
                    @error('kategori')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Deskripsi --}}
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea name="deskripsi" 
                              id="deskripsi"
                              rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('deskripsi') border-red-500 @enderror"
                              placeholder="Deskripsikan galeri ini...">{{ old('deskripsi', $galeri->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Multiple Gambar --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Gambar
                        <span class="text-xs text-gray-500 ml-2">(Upload gambar baru untuk menambah atau mengganti)</span>
                    </label>
                    
                    {{-- Existing Images --}}
                    @if($galeri->gambar->count() > 0)
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Gambar Existing:</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4" id="existing-images">
                            @foreach($galeri->gambar as $gambar)
                            <div class="relative group" data-image-id="{{ $gambar->id }}" data-urutan="{{ $gambar->urutan }}">
                                <div class="aspect-w-1 aspect-h-1 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                    <img src="{{ $gambar->url }}" 
                                         class="w-full h-32 object-cover"
                                         alt="{{ $galeri->judul }}"
                                         onerror="this.onerror=null; this.src='{{ asset('images/no-image.jpg') }}';">
                                </div>
                                
                                {{-- Delete Button --}}
                                <button type="button" 
                                        onclick="deleteExistingImage({{ $gambar->id }})"
                                        class="absolute -top-2 -right-2 p-1.5 bg-red-600 text-white rounded-full hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors opacity-0 group-hover:opacity-100"
                                        title="Hapus Gambar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                
                                {{-- Urutan Badge --}}
                                <span class="absolute top-1 left-1 bg-blue-600 text-white text-xs px-1.5 py-0.5 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                    Urutan: {{ $gambar->urutan + 1 }}
                                </span>
                                
                                {{-- Main Image Badge --}}
                                @if($loop->first)
                                <span class="absolute bottom-1 left-1 bg-green-600 text-white text-xs px-1.5 py-0.5 rounded">
                                    Cover
                                </span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        
                        {{-- Hidden input untuk menyimpan ID gambar yang dihapus --}}
                        <input type="hidden" name="deleted_images" id="deleted_images" value="">
                    </div>
                    @endif
                    
                    {{-- Dropzone Area untuk Upload Baru --}}
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-blue-500 transition-colors cursor-pointer mt-4"
                         id="dropzone">
                        <div class="text-center">
                            {{-- Icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" 
                                 class="mx-auto h-12 w-12 text-gray-400" 
                                 fill="none" 
                                 viewBox="0 0 24 24" 
                                 stroke="currentColor">
                                <path stroke-linecap="round" 
                                      stroke-linejoin="round" 
                                      stroke-width="2" 
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            
                            {{-- Text --}}
                            <div class="mt-2">
                                <span class="text-sm text-gray-600">Drag and drop atau</span>
                                <label for="gambar" 
                                       class="mx-1 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 cursor-pointer text-sm font-medium transition-colors">
                                    Pilih File
                                </label>
                                <span class="text-sm text-gray-600">untuk upload gambar baru</span>
                            </div>
                            
                            {{-- Info --}}
                            <p class="text-xs text-gray-500 mt-1">
                                Format: JPG, JPEG, PNG, GIF (Max 5MB per file, maksimal 10 gambar total)
                            </p>
                            
                            {{-- Hidden Input --}}
                            <input type="file" 
                                   id="gambar"
                                   name="gambar[]" 
                                   class="hidden"
                                   multiple
                                   accept="image/jpeg,image/png,image/jpg,image/gif">
                        </div>
                    </div>
                    
                    {{-- Error Messages --}}
                    @error('gambar')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('gambar.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    
                    {{-- File Info for New Uploads --}}
                    <div id="file-info" class="mt-2 text-sm text-gray-600 hidden">
                        <span id="file-count">0</span> file baru dipilih
                    </div>
                    
                    {{-- Preview Container for New Uploads --}}
                    <div id="image-previews" class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4"></div>
                </div>
                
                {{-- Tanggal & Lokasi --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Tanggal --}}
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="tanggal"
                               name="tanggal" 
                               value="{{ old('tanggal', $galeri->tanggal->format('Y-m-d')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tanggal') border-red-500 @enderror"
                               required>
                        @error('tanggal')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- Lokasi --}}
                    <div>
                        <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-2">
                            Lokasi
                        </label>
                        <input type="text" 
                               id="lokasi"
                               name="lokasi" 
                               value="{{ old('lokasi', $galeri->lokasi) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('lokasi') border-red-500 @enderror"
                               placeholder="Contoh: Aula Utama, Lapangan Sekolah">
                        @error('lokasi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                {{-- Status Publish --}}
                <div class="bg-gray-50 p-4 rounded-lg">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="is_published" 
                               value="1"
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                               {{ old('is_published', $galeri->is_published) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">
                            Publish
                        </span>
                        <span class="ml-2 text-xs text-gray-500">
                            (Jika tidak dicentang, galeri akan disimpan sebagai draft)
                        </span>
                    </label>
                </div>
            </div>
            
            {{-- Action Buttons --}}
            <div class="fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-slate-200 z-50 md:relative md:p-0 md:bg-transparent md:border-none md:z-auto md:mt-8 md:flex md:justify-end md:space-x-3 md:pt-6 md:border-t">
                <div class="flex flex-col sm:flex-row md:flex-row gap-3">
                    <button type="submit" 
                            id="btnSubmit"
                            class="w-full sm:w-auto px-6 py-3 md:py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 order-1 sm:order-2">
                        Update Galeri
                    </button>
                    <a href="{{ route('admin.galeri.index') }}" 
                       class="w-full sm:w-auto text-center px-6 py-3 md:py-2 border border-slate-300 text-slate-700 font-bold rounded-lg hover:bg-slate-50 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 order-2 sm:order-1">
                        Batal
                    </a>
                </div>
            </div>
            
            {{-- Spacer for mobile sticky footer --}}
            <div class="h-24 md:hidden"></div>
        </form>
    </div>
</div>

{{-- Form untuk delete gambar individual --}}
<form id="delete-image-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('styles')
<style>
    .border-dashed {
        transition: all 0.2s ease;
    }
    .border-dashed:hover {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }
    .preview-item {
        animation: fadeIn 0.3s ease;
    }
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    .group:hover .group-hover\:opacity-100 {
        opacity: 1;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('gambar');
    const previewContainer = document.getElementById('image-previews');
    const dropzone = document.getElementById('dropzone');
    const fileInfo = document.getElementById('file-info');
    const fileCount = document.getElementById('file-count');
    const btnSubmit = document.getElementById('btnSubmit');
    const form = document.getElementById('formGaleri');
    
    // Maximum files
    const MAX_FILES = 10;
    const MAX_SIZE = 5 * 1024 * 1024; // 5MB
    
    // Existing images count
    const existingImagesCount = {{ $galeri->gambar->count() }};
    
    // Click dropzone to trigger file input
    dropzone.addEventListener('click', function(e) {
        if (e.target.tagName !== 'LABEL' && e.target.tagName !== 'INPUT') {
            imageInput.click();
        }
    });
    
    // Prevent click on label from triggering twice
    dropzone.querySelector('label').addEventListener('click', function(e) {
        e.stopPropagation();
    });
    
    // Handle file selection
    imageInput.addEventListener('change', handleFileSelect);
    
    // Drag and drop events
    dropzone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('border-blue-500', 'bg-blue-50');
    });
    
    dropzone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('border-blue-500', 'bg-blue-50');
    });
    
    dropzone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('border-blue-500', 'bg-blue-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            imageInput.files = files;
            handleFileSelect({ target: imageInput });
        }
    });
    
    function handleFileSelect(e) {
        const files = Array.from(e.target.files);
        const totalImages = existingImagesCount + files.length;
        
        // Validate total files
        if (totalImages > MAX_FILES) {
            alert(`Total maksimal ${MAX_FILES} gambar (termasuk gambar existing). Saat ini sudah ada ${existingImagesCount} gambar.`);
            e.target.value = '';
            previewContainer.innerHTML = '';
            fileInfo.classList.add('hidden');
            return;
        }
        
        // Validate jumlah file baru
        if (files.length > MAX_FILES) {
            alert(`Maksimal ${MAX_FILES} file yang dapat diupload sekaligus!`);
            e.target.value = '';
            previewContainer.innerHTML = '';
            fileInfo.classList.add('hidden');
            return;
        }
        
        // Clear previous previews
        previewContainer.innerHTML = '';
        
        // Update file info
        if (files.length > 0) {
            fileInfo.classList.remove('hidden');
            fileCount.textContent = files.length;
        } else {
            fileInfo.classList.add('hidden');
        }
        
        // Process each file
        files.forEach((file, index) => {
            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert(`File "${file.name}" bukan gambar!`);
                return;
            }
            
            // Validate file size
            if (file.size > MAX_SIZE) {
                alert(`File "${file.name}" melebihi 5MB!`);
                return;
            }
            
            // Create preview
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const previewItem = document.createElement('div');
                previewItem.className = 'preview-item relative group';
                
                previewItem.innerHTML = `
                    <div class="aspect-w-1 aspect-h-1 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                        <img src="${e.target.result}" 
                             class="w-full h-32 object-cover"
                             alt="Preview ${index + 1}">
                    </div>
                    <button type="button" 
                            onclick="removePreview(this, ${index})"
                            class="absolute -top-2 -right-2 p-1.5 bg-red-600 text-white rounded-full hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                            title="Hapus">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <div class="absolute bottom-1 left-1 right-1">
                        <span class="block truncate text-xs text-white bg-black bg-opacity-75 px-2 py-1 rounded">
                            ${file.name.length > 20 ? file.name.substring(0, 17) + '...' : file.name}
                        </span>
                    </div>
                    <span class="absolute top-1 left-1 bg-blue-600 text-white text-xs px-1.5 py-0.5 rounded">
                        Baru
                    </span>
                `;
                
                previewContainer.appendChild(previewItem);
            }
            
            reader.readAsDataURL(file);
        });
    }
    
    // Form validation before submit
    form.addEventListener('submit', function(e) {
        const files = imageInput.files;
        const totalImages = existingImagesCount + files.length;
        
        if (totalImages === 0) {
            e.preventDefault();
            alert('Minimal 1 gambar harus ada!');
            return;
        }
        
        if (totalImages > MAX_FILES) {
            e.preventDefault();
            alert(`Total maksimal ${MAX_FILES} gambar!`);
            return;
        }
        
        // Disable submit button to prevent double submission
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Menyimpan...
        `;
    });
});

// Remove preview and update file input
window.removePreview = function(button, index) {
    const previewItem = button.closest('.preview-item');
    previewItem.remove();
    
    // Update file input
    const imageInput = document.getElementById('gambar');
    const dt = new DataTransfer();
    
    Array.from(imageInput.files).forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });
    
    imageInput.files = dt.files;
    
    // Update file count
    const fileInfo = document.getElementById('file-info');
    const fileCount = document.getElementById('file-count');
    
    if (imageInput.files.length > 0) {
        fileInfo.classList.remove('hidden');
        fileCount.textContent = imageInput.files.length;
    } else {
        fileInfo.classList.add('hidden');
    }
}

// Delete existing image
window.deleteExistingImage = function(imageId) {
    if (!confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
        return;
    }
    
    // Add to deleted images input
    const deletedImages = document.getElementById('deleted_images');
    const currentValue = deletedImages.value;
    const newValue = currentValue ? currentValue + ',' + imageId : imageId;
    deletedImages.value = newValue;
    
    // Remove from UI
    const imageElement = document.querySelector(`[data-image-id="${imageId}"]`);
    if (imageElement) {
        imageElement.remove();
    }
}
</script>
@endpush
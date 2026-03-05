{{-- resources/views/Home/galeri/show.blade.php --}}
@extends('layouts.frontend')

@section('title', $galeri->judul . ' - Galeri TK Harapan Bangsa')

@push('meta')
<meta property="og:title" content="{{ $galeri->judul }}">
<meta property="og:description" content="{{ Str::limit(strip_tags($galeri->deskripsi), 150) }}">
<meta property="og:image" content="{{ $galeri->gambar_url }}">
<meta property="og:type" content="article">
<meta name="twitter:card" content="summary_large_image">
@endpush

@push('styles')
<style>
    /* Gallery Lightbox Animation */
    .lightbox-enter {
        opacity: 0;
        transform: scale(0.9);
    }
    .lightbox-enter-active {
        opacity: 1;
        transform: scale(1);
        transition: opacity 0.3s, transform 0.3s;
    }
    .lightbox-exit {
        opacity: 1;
        transform: scale(1);
    }
    .lightbox-exit-active {
        opacity: 0;
        transform: scale(0.9);
        transition: opacity 0.3s, transform 0.3s;
    }
    
    /* Image Zoom Effect */
    .gallery-thumb {
        transition: transform 0.3s ease;
    }
    .gallery-thumb:hover {
        transform: scale(1.05);
    }
    
    /* Custom Scrollbar */
    .gallery-grid::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    .gallery-grid::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    .gallery-grid::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }
    .gallery-grid::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
@endpush

@section('content')
<!-- Hero Section with Gallery Stats -->
<div class="relative bg-gradient-to-br from-blue-600 to-blue-800 text-white py-12">
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-white opacity-10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-white opacity-10 rounded-full blur-3xl"></div>
    </div>
    
    <div class="container mx-auto px-4 relative z-10">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3 flex-wrap">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="text-white/80 hover:text-white transition-colors">
                        <i class="fas fa-home mr-1"></i>
                        Beranda
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-white/50 mx-2 text-sm"></i>
                        <a href="{{ route('galeri.index') }}" class="text-white/80 hover:text-white transition-colors">
                            Galeri
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-white/50 mx-2 text-sm"></i>
                        <span class="text-white truncate max-w-xs md:max-w-md">
                            {{ $galeri->judul }}
                        </span>
                    </div>
                </li>
            </ol>
        </nav>
        
        <!-- Gallery Title & Quick Stats -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium">
                        <i class="fas fa-camera mr-1"></i>
                        {{ ucfirst($galeri->kategori) }}
                    </span>
                    <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium">
                        <i class="fas fa-images mr-1"></i>
                        {{ $galeri->gambar_count }} Foto
                    </span>
                </div>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4 leading-tight">
                    {{ $galeri->judul }}
                </h1>
                
                <div class="flex flex-wrap items-center gap-4 text-white/80">
                    <div class="flex items-center">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        {{ \Carbon\Carbon::parse($galeri->tanggal)->isoFormat('dddd, D MMMM Y') }}
                    </div>
                    @if($galeri->lokasi)
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        {{ $galeri->lokasi }}
                    </div>
                    @endif
                    <div class="flex items-center">
                        <i class="fas fa-eye mr-2"></i>
                        {{ number_format($galeri->views) }} dilihat
                    </div>
                </div>
            </div>
            
            <!-- Share Button -->
            <button onclick="shareGallery()" 
                    class="w-full lg:w-auto justify-center inline-flex items-center px-6 py-3 bg-white text-blue-600 rounded-xl hover:bg-blue-50 transition-all duration-300 transform hover:scale-105 shadow-lg group mt-4 lg:mt-0">
                <i class="fas fa-share-alt mr-2 group-hover:rotate-12 transition-transform"></i>
                Bagikan Galeri
            </button>
        </div>
    </div>
</div>

<!-- Main Content -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4 max-w-7xl">
        <!-- Featured Image Card -->
        @if($galeri->gambar->isNotEmpty())
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8 transform hover:shadow-2xl transition-shadow duration-300">
            <div class="relative group">
                <img src="{{ $galeri->gambar->first()->url }}" 
                     alt="{{ $galeri->judul }}"
                     class="w-full h-[500px] object-cover cursor-pointer"
                     id="featuredImage"
                     onclick="openLightbox(0)">
                
                <!-- Image Overlay with Actions -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <div class="absolute bottom-4 left-4 right-4 flex justify-between items-center">
                        <span class="text-white text-sm bg-black/50 px-3 py-1 rounded-full backdrop-blur-sm">
                            <i class="fas fa-camera mr-1"></i> Foto Utama
                        </span>
                        <div class="flex space-x-2">
                            <button onclick="downloadImage('{{ $galeri->gambar->first()->url }}')" 
                                    class="bg-white/90 backdrop-blur-sm p-2 rounded-full hover:bg-white transition-colors"
                                    title="Download">
                                <i class="fas fa-download text-gray-700"></i>
                            </button>
                            <button onclick="openLightbox(0)" 
                                    class="bg-white/90 backdrop-blur-sm p-2 rounded-full hover:bg-white transition-colors"
                                    title="Perbesar">
                                <i class="fas fa-expand text-gray-700"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content - Left Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Description Card -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-white border-b border-gray-100">
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Tentang Kegiatan
                        </h2>
                    </div>
                    <div class="p-6">
                        @if($galeri->deskripsi)
                            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                                {!! nl2br(e($galeri->deskripsi)) !!}
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-pen text-gray-400 text-3xl"></i>
                                </div>
                                <p class="text-gray-500 italic">Belum ada deskripsi untuk galeri ini</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- All Images Gallery -->
                @if($galeri->gambar->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-white border-b border-gray-100 flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-images text-blue-600 mr-2"></i>
                            Semua Foto ({{ $galeri->gambar->count() }})
                        </h2>
                        
                        <button onclick="startSlideshow()" 
                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all duration-300 text-sm">
                            <i class="fas fa-play mr-2"></i>
                            Slide Show
                        </button>
                    </div>
                    
                    <div class="p-6">
                        <!-- Masonry Grid Gallery -->
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($galeri->gambar as $index => $gambar)
                            <div class="relative group overflow-hidden rounded-xl bg-gray-100 cursor-pointer shadow-md hover:shadow-xl transition-all duration-300"
                                 onclick="openLightbox({{ $index }})">
                                
                                <img src="{{ $gambar->url }}" 
                                     alt="Foto {{ $index + 1 }}"
                                     class="w-full h-48 object-cover gallery-thumb"
                                     loading="lazy"
                                     onerror="this.onerror=null; this.src='{{ asset('images/no-image.jpg') }}';">
                                
                                <!-- Image Overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="absolute bottom-2 right-2 flex space-x-1">
                                        <button onclick="event.stopPropagation(); downloadImage('{{ $gambar->url }}')" 
                                                class="bg-white/90 backdrop-blur-sm p-1.5 rounded-lg hover:bg-white transition-colors">
                                            <i class="fas fa-download text-xs text-gray-700"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Index Badge (Mobile) -->
                                <span class="absolute top-2 left-2 bg-black/50 backdrop-blur-sm text-white text-xs px-2 py-1 rounded-lg md:hidden">
                                    {{ $index + 1 }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Sidebar - Right Column -->
            <div class="space-y-6">
                <!-- Info Card -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden sticky top-24">
                    <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-white border-b border-gray-100">
                        <h3 class="font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-info-circle text-purple-600 mr-2"></i>
                            Informasi Galeri
                        </h3>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <!-- Date -->
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-calendar-alt text-purple-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-500">Tanggal Kegiatan</p>
                                <p class="font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($galeri->tanggal)->isoFormat('dddd, D MMMM Y') }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Location -->
                        @if($galeri->lokasi)
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-green-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-500">Lokasi</p>
                                <p class="font-medium text-gray-900">{{ $galeri->lokasi }}</p>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Category -->
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-tag text-blue-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-500">Kategori</p>
                                <p class="font-medium text-gray-900 capitalize">{{ $galeri->kategori }}</p>
                            </div>
                        </div>
                        
                        <!-- Total Photos -->
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-camera text-orange-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-500">Total Foto</p>
                                <p class="font-medium text-gray-900">{{ $galeri->gambar->count() }} foto</p>
                            </div>
                        </div>
                        
                        <!-- Views -->
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-eye text-red-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-500">Total Dilihat</p>
                                <p class="font-medium text-gray-900">{{ number_format($galeri->views) }} kali</p>
                            </div>
                        </div>
                        
                        <!-- Created By -->
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user text-indigo-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-500">Diupload oleh</p>
                                <p class="font-medium text-gray-900">{{ $galeri->user->name ?? 'Admin' }}</p>
                            </div>
                        </div>
                        
                        <!-- Created At -->
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-clock text-gray-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-500">Diupload pada</p>
                                <p class="font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($galeri->created_at)->isoFormat('D MMMM Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Share Buttons -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Bagikan ke:</h4>
                        <div class="flex space-x-2">
                            <button onclick="shareToFacebook()" 
                                    class="flex-1 bg-blue-600 text-white py-2 rounded-xl hover:bg-blue-700 transition-colors text-sm flex items-center justify-center">
                                <i class="fab fa-facebook-f mr-1"></i>
                                Facebook
                            </button>
                            <button onclick="shareToTwitter()" 
                                    class="flex-1 bg-blue-400 text-white py-2 rounded-xl hover:bg-blue-500 transition-colors text-sm flex items-center justify-center">
                                <i class="fab fa-twitter mr-1"></i>
                                Twitter
                            </button>
                            <button onclick="shareToWhatsApp()" 
                                    class="flex-1 bg-green-500 text-white py-2 rounded-xl hover:bg-green-600 transition-colors text-sm flex items-center justify-center">
                                <i class="fab fa-whatsapp mr-1"></i>
                                WA
                            </button>
                        </div>
                        <button onclick="copyLink()" 
                                class="w-full mt-2 bg-gray-700 text-white py-2 rounded-xl hover:bg-gray-800 transition-colors text-sm flex items-center justify-center">
                            <i class="fas fa-link mr-1"></i>
                            Salin Tautan
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Galleries -->
        @if($related->count() > 0)
        <div class="mt-16">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-images text-blue-600 mr-2"></i>
                    Galeri Lainnya
                </h2>
                <a href="{{ route('galeri.index') }}" class="text-blue-600 hover:text-blue-700 font-medium flex items-center">
                    Lihat Semua
                    <i class="fas fa-arrow-right ml-1 text-sm"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($related as $item)
                <div class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <a href="{{ route('galeri.show', $item->slug) }}" class="block">
                        <div class="relative h-48 overflow-hidden">
                            @if($item->gambar->isNotEmpty())
                            <img src="{{ $item->gambar->first()->url }}" 
                                 alt="{{ $item->judul }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                 loading="lazy">
                            @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-4xl"></i>
                            </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <!-- Overlay Info -->
                            <div class="absolute bottom-0 left-0 right-0 p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                <span class="text-white text-sm bg-blue-600 px-2 py-1 rounded inline-block">
                                    <i class="fas fa-camera mr-1"></i>
                                    {{ $item->gambar->count() }} foto
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors line-clamp-2">
                                {{ $item->judul }}
                            </h3>
                            
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-calendar-alt mr-2 text-xs"></i>
                                {{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('D MMM Y') }}
                            </div>
                            
                            <div class="flex items-center mt-2 text-xs text-gray-400">
                                <i class="fas fa-eye mr-1"></i>
                                {{ number_format($item->views) }} dilihat
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- Back Button -->
        <div class="mt-12 text-center px-4 sm:px-0">
            <a href="{{ route('galeri.index') }}" 
               class="w-full sm:w-auto justify-center inline-flex items-center px-8 py-4 bg-white border-2 border-blue-600 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all duration-300 transform hover:scale-105 font-semibold shadow-lg hover:shadow-xl">
                <i class="fas fa-arrow-left mr-2"></i> 
                Kembali ke Galeri
            </a>
        </div>
    </div>
</section>

<!-- Lightbox Modal -->
<div id="lightboxModal" class="fixed inset-0 bg-black bg-opacity-95 z-50 hidden transition-opacity duration-300">
    <div class="absolute top-4 right-4 z-50 flex space-x-2">
        <!-- Slideshow Control -->
        <button onclick="toggleSlideshow()" 
                class="bg-white/10 hover:bg-white/20 text-white rounded-full p-3 transition-all duration-300 backdrop-blur-sm"
                id="slideshowBtn">
            <i id="playIcon" class="fas fa-play"></i>
            <i id="pauseIcon" class="fas fa-pause hidden"></i>
        </button>
        
        <!-- Download Button -->
        <button onclick="downloadCurrentImage()" 
                class="bg-white/10 hover:bg-white/20 text-white rounded-full p-3 transition-all duration-300 backdrop-blur-sm">
            <i class="fas fa-download"></i>
        </button>
        
        <!-- Close Button -->
        <button onclick="closeLightbox()" 
                class="bg-white/10 hover:bg-white/20 text-white rounded-full p-3 transition-all duration-300 backdrop-blur-sm">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <!-- Navigation Buttons -->
    <button onclick="prevImage()" 
            class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/10 hover:bg-white/20 text-white rounded-full p-4 transition-all duration-300 backdrop-blur-sm">
        <i class="fas fa-chevron-left text-xl"></i>
    </button>
    
    <button onclick="nextImage()" 
            class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/10 hover:bg-white/20 text-white rounded-full p-4 transition-all duration-300 backdrop-blur-sm">
        <i class="fas fa-chevron-right text-xl"></i>
    </button>
    
    <!-- Image Container -->
    <div class="flex items-center justify-center h-full p-4">
        <div class="relative max-w-7xl max-h-full">
            <img id="lightboxImage" src="" alt="" class="max-w-full max-h-screen object-contain rounded-lg">
            
            <!-- Image Info -->
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent p-6 rounded-b-lg">
                <div class="flex justify-between items-end text-white">
                    <div>
                        <p class="text-lg font-medium" id="lightboxTitle">{{ $galeri->judul }}</p>
                        <p class="text-sm opacity-75" id="lightboxCounter"></p>
                    </div>
                    <p class="text-sm opacity-75">{{ \Carbon\Carbon::parse($galeri->tanggal)->isoFormat('D MMMM Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Gallery Data - Menggunakan relasi gambar
    const images = @json($galeri->gambar->map(function($gambar) {
        return [
            'url' => $gambar->url,
            'name' => $gambar->nama_file ?? 'Foto'
        ];
    }));
    
    let currentIndex = 0;
    let slideshowInterval = null;
    let isPlaying = false;
    
    // Lightbox Functions
    function openLightbox(index) {
        if (images.length === 0) return;
        
        currentIndex = index;
        updateLightboxImage();
        document.getElementById('lightboxModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Add animation class
        const modal = document.getElementById('lightboxModal');
        modal.classList.add('lightbox-enter-active');
        setTimeout(() => {
            modal.classList.remove('lightbox-enter-active');
        }, 300);
    }
    
    function closeLightbox() {
        const modal = document.getElementById('lightboxModal');
        modal.classList.add('lightbox-exit-active');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('lightbox-exit-active');
            document.body.style.overflow = '';
            stopSlideshow();
        }, 300);
    }
    
    function updateLightboxImage() {
        if (images.length > 0) {
            const img = document.getElementById('lightboxImage');
            img.src = images[currentIndex].url;
            img.alt = `Foto ${currentIndex + 1}`;
            
            document.getElementById('lightboxCounter').innerHTML = 
                `Foto ${currentIndex + 1} dari ${images.length}`;
            
            // Update featured image if exists
            const featuredImg = document.getElementById('featuredImage');
            if (featuredImg) {
                featuredImg.src = images[currentIndex].url;
            }
        }
    }
    
    function prevImage() {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        updateLightboxImage();
    }
    
    function nextImage() {
        currentIndex = (currentIndex + 1) % images.length;
        updateLightboxImage();
    }
    
    // Slideshow Functions
    function startSlideshow() {
        if (images.length === 0) return;
        
        openLightbox(0);
        setTimeout(() => {
            startSlideshowTimer();
        }, 300);
    }
    
    function startSlideshowTimer() {
        if (slideshowInterval) clearInterval(slideshowInterval);
        
        slideshowInterval = setInterval(() => {
            nextImage();
        }, 3000);
        
        isPlaying = true;
        updateSlideshowIcons();
    }
    
    function stopSlideshow() {
        if (slideshowInterval) {
            clearInterval(slideshowInterval);
            slideshowInterval = null;
        }
        isPlaying = false;
        updateSlideshowIcons();
    }
    
    function toggleSlideshow() {
        if (isPlaying) {
            stopSlideshow();
        } else {
            startSlideshowTimer();
        }
    }
    
    function updateSlideshowIcons() {
        const playIcon = document.getElementById('playIcon');
        const pauseIcon = document.getElementById('pauseIcon');
        
        if (playIcon && pauseIcon) {
            if (isPlaying) {
                playIcon.classList.add('hidden');
                pauseIcon.classList.remove('hidden');
            } else {
                playIcon.classList.remove('hidden');
                pauseIcon.classList.add('hidden');
            }
        }
    }
    
    // Download Functions
    function downloadImage(url) {
        const link = document.createElement('a');
        link.href = url;
        link.download = url.split('/').pop() || 'gallery-image.jpg';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
    
    function downloadCurrentImage() {
        if (images.length > 0) {
            downloadImage(images[currentIndex].url);
        }
    }
    
    // Share Functions
    function shareToFacebook() {
        const url = window.location.href;
        window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, '_blank', 'width=600,height=400');
    }

    function shareToTwitter() {
        const url = window.location.href;
        const text = `{{ $galeri->judul }}`;
        window.open(`https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(text)}`, '_blank', 'width=600,height=400');
    }

    function shareToWhatsApp() {
        const url = window.location.href;
        const text = `{{ $galeri->judul }} - ${url}`;
        window.open(`https://api.whatsapp.com/send?text=${encodeURIComponent(text)}`, '_blank');
    }

    function copyLink() {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            showNotification('Tautan berhasil disalin!', 'success');
        }).catch(() => {
            showNotification('Gagal menyalin tautan', 'error');
        });
    }

    function shareGallery() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $galeri->judul }}',
                text: '{{ Str::limit(strip_tags($galeri->deskripsi), 100) }}',
                url: window.location.href,
            }).catch(console.error);
        } else {
            copyLink();
        }
    }
    
    // Notification System
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 animate-slideIn ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } text-white`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('animate-slideOut');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
    
    // Keyboard Navigation
    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('lightboxModal');
        if (!modal.classList.contains('hidden')) {
            if (e.key === 'Escape') {
                closeLightbox();
            } else if (e.key === 'ArrowLeft') {
                prevImage();
            } else if (e.key === 'ArrowRight') {
                nextImage();
            } else if (e.key === ' ') {
                e.preventDefault();
                toggleSlideshow();
            }
        }
    });
    
    // Touch Navigation for Mobile
    let touchStartX = 0;
    let touchEndX = 0;
    
    const lightboxModal = document.getElementById('lightboxModal');
    if (lightboxModal) {
        lightboxModal.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        }, false);
        
        lightboxModal.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, false);
    }
    
    function handleSwipe() {
        const swipeThreshold = 50;
        if (touchEndX < touchStartX - swipeThreshold) {
            nextImage();
        }
        if (touchEndX > touchStartX + swipeThreshold) {
            prevImage();
        }
    }
    
    // Clean up on page unload
    window.addEventListener('beforeunload', function() {
        if (slideshowInterval) {
            clearInterval(slideshowInterval);
        }
    });
    
    // Add animation styles
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        .animate-slideIn {
            animation: slideIn 0.3s ease-out;
        }
        
        .animate-slideOut {
            animation: slideOut 0.3s ease-out;
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    `;
    document.head.appendChild(style);
</script>
@endpush
{{-- resources/views/galeri/index.blade.php --}}
@extends('layouts.frontend')

@section('title', 'Galeri Sekolah - TK Harapan Bangsa 1')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-orange-500 to-pink-600 py-12">
    <div class="container mx-auto px-4 text-center text-white">
        <h1 class="text-3xl md:text-4xl font-bold mb-4">Galeri Sekolah</h1>
        <p class="text-lg md:text-xl opacity-90">Kumpulan momen berharga dan kegiatan siswa TK Harapan Bangsa 1</p>
    </div>
</div>

<!-- Gallery Section -->
<div class="container mx-auto px-4 py-8">
    <!-- Category Filter -->
    <div class="mb-8">
        <div class="flex flex-wrap gap-2 justify-center">
            <a href="{{ route('galeri.index') }}" 
               class="px-4 py-2 rounded-full {{ !request('kategori') ? 'bg-orange-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Semua Kategori
            </a>
            @foreach($kategoriList as $kat)
                <a href="{{ route('galeri.index', ['kategori' => $kat]) }}" 
                   class="px-4 py-2 rounded-full {{ request('kategori') == $kat ? 'bg-orange-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ ucfirst($kat) }}
                </a>
            @endforeach
        </div>
    </div>

    @if($galeri->count() > 0)
    <!-- Gallery Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($galeri as $item)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 hover:shadow-md transition-shadow flex flex-col h-full">
            <div class="relative overflow-hidden h-48">
                <a href="{{ route('galeri.show', $item->slug) }}">
                    <img src="{{ $item->thumbnail_url }}" 
                         alt="{{ $item->judul }}"
                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                         onerror="this.onerror=null; this.src='{{ asset('images/no-image.jpg') }}'; this.classList.add('object-contain', 'p-4', 'bg-gray-100');">
                </a>
                
                <!-- Badge Kategori -->
                <div class="absolute top-3 left-3">
                    <span class="px-3 py-1 text-xs font-medium bg-white/90 backdrop-blur-sm rounded-full shadow-sm">
                        {{ ucfirst($item->kategori) }}
                    </span>
                </div>
                
                <!-- Badge jumlah gambar jika lebih dari 1 -->
                @if($item->jumlah_gambar > 1)
                <div class="absolute top-3 right-3">
                    <span class="px-3 py-1 text-xs font-medium bg-black/70 text-white rounded-full shadow-sm">
                        <i class="fas fa-images mr-1"></i>
                        {{ $item->jumlah_gambar }}
                    </span>
                </div>
                @endif
            </div>
            
            <div class="p-5 flex-1 flex flex-col">
                <div class="flex items-center text-sm text-gray-500 mb-2">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    {{ $item->tanggal ? $item->tanggal->format('d M Y') : now()->format('d M Y') }}
                    <span class="mx-2">•</span>
                    <i class="fas fa-eye mr-1"></i>
                    {{ number_format($item->views ?? 0) }}
                </div>
                
                <h3 class="text-lg font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-orange-600">
                    <a href="{{ route('galeri.show', $item->slug) }}" class="hover:text-orange-600 transition-colors">
                        {{ $item->judul }}
                    </a>
                </h3>
                
                @if($item->deskripsi)
                <div class="text-gray-600 mb-4 line-clamp-2 text-sm flex-1">
                    {{ Str::limit(strip_tags($item->deskripsi), 80) }}
                </div>
                @else
                <div class="flex-1"></div>
                @endif
                
                <a href="{{ route('galeri.show', $item->slug) }}"
                   class="inline-flex items-center text-orange-600 hover:text-orange-800 font-medium text-sm mt-auto w-fit">
                    Lihat Detail
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($galeri->hasPages())
    <div class="mt-8">
        {{ $galeri->onEachSide(1)->links('vendor.pagination.tailwind') }}
    </div>
    @endif

    @else
    <!-- Empty State (Konsisten dengan Berita) -->
    <div class="text-center py-12">
        <div class="text-gray-400 mb-4">
            <i class="fas fa-images text-5xl"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada Galeri</h3>
        <p class="text-gray-500">Tidak ada galeri yang tersedia saat ini.</p>
        <div class="mt-6">
            <a href="{{ route('home') }}" 
               class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors duration-300 shadow-md hover:shadow-lg">
                <i class="fas fa-home mr-2"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
    @endif
</div>

<!-- CTA Section -->
<section class="bg-gradient-to-br from-orange-50 to-pink-100 py-16 mt-8">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Ingin Melihat Lebih Banyak?</h2>
        <p class="text-gray-600 mb-8 max-w-2xl mx-auto">
            Ikuti terus perkembangan kegiatan siswa kami melalui galeri ini. 
            Banyak momen berharga yang kami bagikan untuk Anda.
        </p>
        <a href="{{ route('home') }}" 
           class="inline-flex items-center px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors duration-300 shadow-md hover:shadow-lg">
            <i class="fas fa-home mr-2"></i> Kembali ke Beranda
        </a>
    </div>
</section>
@endsection

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush
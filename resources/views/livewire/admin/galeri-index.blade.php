<div>
    {{-- Toolbar: Search, Filter, Add --}}
    <div class="bg-white rounded-2xl p-4 shadow-sm mb-8 flex flex-wrap items-center justify-between gap-4 border border-slate-100">
        <div class="flex flex-wrap items-center gap-4 flex-1">
            {{-- Search --}}
            <div class="relative flex-1 max-w-sm">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Cari album..."
                       class="w-full pl-10 pr-4 py-2 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 text-sm transition-all"/>
            </div>

            {{-- Kategori Filter --}}
            <select wire:model.live="kategori"
                    class="border border-slate-200 rounded-xl px-4 py-2 text-sm bg-slate-50 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all">
                <option value="">Semua Kategori</option>
                @foreach($kategoriList as $kat)
                    <option value="{{ $kat }}">
                        {{ ucfirst($kat) }}
                    </option>
                @endforeach
            </select>

            {{-- Status Filter --}}
            <div class="flex items-center bg-slate-50 rounded-xl p-1">
                <button type="button" wire:click="$set('status', '')"
                        class="px-4 py-1.5 text-sm font-medium rounded-lg transition-colors {{ $status === '' ? 'bg-white text-primary shadow-sm font-semibold' : 'text-slate-500 hover:text-primary' }}">
                    Semua
                </button>
                <button type="button" wire:click="$set('status', 'published')"
                        class="px-4 py-1.5 text-sm font-medium rounded-lg transition-colors {{ $status === 'published' ? 'bg-white text-primary shadow-sm font-semibold' : 'text-slate-500 hover:text-primary' }}">
                    Published
                </button>
                <button type="button" wire:click="$set('status', 'draft')"
                        class="px-4 py-1.5 text-sm font-medium rounded-lg transition-colors {{ $status === 'draft' ? 'bg-white text-primary shadow-sm font-semibold' : 'text-slate-500 hover:text-primary' }}">
                    Draft
                </button>
            </div>

            @if($search || $kategori || $status)
                <button type="button" wire:click="resetFilters"
                   class="px-4 py-2 text-sm font-medium text-slate-500 hover:text-primary border border-slate-200 rounded-xl bg-slate-50 hover:bg-lavender transition-all">
                    Reset
                </button>
            @endif
        </div>

        {{-- Add Button --}}
        <a href="{{ route('admin.galeri.create') }}"
           class="flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary/90 transition-all shadow-lg shadow-primary/20">
            <span class="material-symbols-outlined text-lg">add_circle</span>
            Tambah Album
        </a>
    </div>

    {{-- Gallery Grid --}}
    @if($galeri->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($galeri as $item)
        <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all border border-slate-100 group">
            {{-- Thumbnail --}}
            <div class="relative aspect-video overflow-hidden">
                <img src="{{ $item->thumbnail_url }}"
                     alt="{{ $item->judul }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                     onerror="this.onerror=null; this.src='{{ asset('images/no-image.jpg') }}'; this.classList.add('p-8','opacity-50');">

                {{-- Status Badge --}}
                <div class="absolute top-3 right-3">
                    @if($item->is_published)
                        <span class="px-2.5 py-1 bg-green-500 text-white text-[10px] font-bold rounded-full uppercase tracking-wider shadow-lg">Public</span>
                    @else
                        <span class="px-2.5 py-1 bg-slate-400 text-white text-[10px] font-bold rounded-full uppercase tracking-wider shadow-lg">Draft</span>
                    @endif
                </div>

                {{-- Category Badge --}}
                <div class="absolute top-3 left-3">
                    <span class="px-2 py-1 text-[10px] font-semibold bg-white/90 backdrop-blur-sm rounded-lg shadow-sm text-slate-700">
                        {{ ucfirst($item->kategori) }}
                    </span>
                </div>

                {{-- Photo count badge --}}
                @if($item->jumlah_gambar > 0)
                <div class="absolute bottom-3 left-3 px-2 py-1 bg-black/40 backdrop-blur-md rounded-lg text-white text-[10px] font-medium flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-xs">image</span>
                    {{ $item->jumlah_gambar }} Foto
                </div>
                @endif
            </div>

            {{-- Card Body --}}
            <div class="p-5">
                <h3 class="font-bold text-slate-800 mb-1 line-clamp-1" title="{{ $item->judul }}">{{ $item->judul }}</h3>
                <p class="text-xs text-slate-400 mb-1">
                    {{ $item->tanggal->format('d M Y') }}
                    &nbsp;·&nbsp;
                    <span class="inline-flex items-center gap-1">
                        <span class="material-symbols-outlined text-xs">visibility</span>
                        {{ number_format($item->views ?? 0) }}
                    </span>
                </p>
                @if($item->deskripsi)
                <p class="text-xs text-slate-500 mb-3 line-clamp-2">{{ Str::limit(strip_tags($item->deskripsi), 80) }}</p>
                @endif

                {{-- Actions --}}
                <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                    <div class="flex gap-1">
                        {{-- View --}}
                        <a href="{{ route('admin.galeri.show', $item) }}"
                           class="p-2 text-slate-400 hover:text-primary hover:bg-lavender rounded-lg transition-all"
                           title="Lihat Detail">
                            <span class="material-symbols-outlined text-xl">visibility</span>
                        </a>
                        {{-- Edit --}}
                        <a href="{{ route('admin.galeri.edit', $item) }}"
                           class="p-2 text-slate-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-all"
                           title="Edit">
                            <span class="material-symbols-outlined text-xl">edit</span>
                        </a>
                        {{-- Toggle Publish --}}
                        <form action="{{ route('admin.galeri.toggle-publish', $item) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="p-2 rounded-lg transition-all {{ $item->is_published ? 'text-yellow-500 hover:bg-yellow-50' : 'text-green-500 hover:bg-green-50' }}"
                                    title="{{ $item->is_published ? 'Unpublish' : 'Publish' }}">
                                <span class="material-symbols-outlined text-xl">{{ $item->is_published ? 'unpublished' : 'publish' }}</span>
                            </button>
                        </form>
                    </div>

                    {{-- Delete --}}
                    <button type="button"
                            onclick="confirmDelete({{ $item->id }})"
                            class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all"
                            title="Hapus">
                        <span class="material-symbols-outlined text-xl">delete</span>
                    </button>
                </div>
            </div>
        </div>
        @endforeach

        {{-- Add New Card --}}
        <a href="{{ route('admin.galeri.create') }}"
           class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl flex flex-col items-center justify-center p-8 hover:bg-lavender hover:border-primary/30 transition-all group min-h-[320px]">
            <div class="w-14 h-14 rounded-full bg-white flex items-center justify-center shadow-sm mb-4 group-hover:bg-primary group-hover:text-white transition-all">
                <span class="material-symbols-outlined text-3xl">add</span>
            </div>
            <p class="font-bold text-slate-500 group-hover:text-primary">Buat Album Baru</p>
            <p class="text-xs text-slate-400 mt-1">Foto atau Video Kegiatan</p>
        </a>
    </div>

    {{-- Pagination --}}
    @if($galeri->hasPages())
    <div class="mt-8">
        {{ $galeri->links('vendor.pagination.tailwind') }}
    </div>
    @endif

    @else
    {{-- Empty State --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm text-center py-20">
        <div class="w-20 h-20 rounded-full bg-lavender flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-4xl text-primary">photo_library</span>
        </div>
        <h3 class="text-xl font-bold text-slate-700 mb-2">Belum Ada Galeri</h3>
        <p class="text-slate-400 text-sm mb-6">Mulai dengan menambahkan galeri pertama Anda.</p>
        <a href="{{ route('admin.galeri.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary/90 transition-all shadow-lg shadow-primary/20">
            <span class="material-symbols-outlined text-lg">add_circle</span>
            Tambah Galeri
        </a>
    </div>
    @endif
</div>

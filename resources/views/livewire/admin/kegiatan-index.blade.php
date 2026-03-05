<div>
    <div class="bg-white p-5 rounded-3xl shadow-sm border border-slate-100 mb-8">
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-6">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 flex-1">
                <a href="{{ route('admin.kegiatan.create') }}" class="flex items-center justify-center gap-2 px-6 py-3.5 bg-primary text-white rounded-2xl font-bold text-sm hover:opacity-90 transition-all shadow-lg shadow-primary/20 whitespace-nowrap">
                    <span class="material-symbols-outlined text-lg">add_circle</span>
                    Tambah Kegiatan
                </a>
                <div class="relative flex-1 group">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">search</span>
                    <input wire:model.live.debounce.300ms="search" 
                           class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 text-sm transition-all" 
                           placeholder="Cari nama kegiatan atau lokasi..." type="text"/>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <div class="flex flex-1 gap-3">
                    <select wire:model.live="kategori" class="flex-1 sm:w-44 bg-slate-50 border border-slate-100 rounded-2xl px-4 py-3.5 text-sm text-slate-600 focus:ring-2 focus:ring-primary/20 transition-all outline-none">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                    
                    <select wire:model.live="status" class="flex-1 sm:w-36 bg-slate-50 border border-slate-100 rounded-2xl px-4 py-3.5 text-sm text-slate-600 focus:ring-2 focus:ring-primary/20 transition-all outline-none">
                        <option value="">Semua Status</option>
                        <option value="published">Public</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>

                @if($search || $kategori || $status)
                    <button wire:click="resetFilters" class="flex items-center justify-center gap-2 px-4 py-3.5 text-red-500 bg-red-50 hover:bg-red-100 rounded-2xl transition-all sm:w-auto" title="Reset Filter">
                        <span class="material-symbols-outlined">filter_alt_off</span>
                        <span class="sm:hidden text-xs font-bold uppercase tracking-wider">Reset</span>
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-5 text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">No</th>
                        <th class="px-6 py-5 text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Nama Kegiatan</th>
                        <th class="px-6 py-5 text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Tanggal Pelaksanaan</th>
                        <th class="px-6 py-5 text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Lokasi</th>
                        <th class="px-6 py-5 text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Status</th>
                        <th class="px-6 py-5 text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($kegiatans as $index => $kegiatan)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-slate-400">
                            {{ $kegiatans->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                @if($kegiatan->banner_path)
                                    <div class="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0 border border-slate-100 shadow-sm">
                                        <img src="{{ $kegiatan->banner_url }}" class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center flex-shrink-0 border border-slate-100 shadow-sm">
                                        <span class="material-symbols-outlined text-slate-300">image</span>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $kegiatan->nama_kegiatan }}</p>
                                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider mt-0.5">{{ $kegiatan->kategori }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $kegiatan->tanggal_mulai->format('d M Y') }}
                            @if($kegiatan->tanggal_selesai)
                                - {{ $kegiatan->tanggal_selesai->format('d M Y') }}
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 text-sm text-slate-600">
                                <span class="material-symbols-outlined text-slate-400 text-lg">location_on</span>
                                {{ $kegiatan->lokasi }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($kegiatan->is_published)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-100 text-green-700 rounded-full text-[10px] font-bold uppercase tracking-wide">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    Public
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-[10px] font-bold uppercase tracking-wide">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Draft
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.kegiatan.show', $kegiatan) }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-100 text-slate-600 hover:bg-primary hover:text-white transition-all" title="Show">
                                    <span class="material-symbols-outlined text-lg">visibility</span>
                                </a>
                                <a href="{{ route('admin.kegiatan.edit', $kegiatan) }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-100 text-slate-600 hover:bg-amber-500 hover:text-white transition-all" title="Edit">
                                    <span class="material-symbols-outlined text-lg">edit</span>
                                </a>
                                <button type="button" onclick="confirmDeleteKegiatan({{ $kegiatan->id }})" class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-100 text-slate-600 hover:bg-red-500 hover:text-white transition-all" title="Delete">
                                    <span class="material-symbols-outlined text-lg">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-400 italic">
                            Belum ada data kegiatan yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-8 py-5 flex items-center justify-between border-t border-slate-50">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                Showing {{ $kegiatans->firstItem() ?? 0 }} to {{ $kegiatans->lastItem() ?? 0 }} of {{ $kegiatans->total() }} events
            </p>
            <div>
                {{ $kegiatans->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
</div>

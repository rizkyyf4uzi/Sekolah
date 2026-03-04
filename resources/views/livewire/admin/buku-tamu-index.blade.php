<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <a href="{{ route('admin.bukutamu.create') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary text-white rounded-xl font-bold text-sm hover:shadow-lg hover:shadow-primary/30 transition-all active:scale-[0.98]">
            <span class="material-symbols-outlined text-lg">add_circle</span>
            Tambah Buku Tamu
        </a>
        <div class="flex items-center gap-3 flex-1 md:max-w-2xl">
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">search</span>
                <input wire:model.live.debounce.300ms="search" class="w-full pl-12 pr-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm shadow-sm transition-all text-slate-700" placeholder="Cari nama pengunjung atau tujuan..." type="text"/>
            </div>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">calendar_today</span>
                <input wire:model.live="tanggal" class="pl-12 pr-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm shadow-sm transition-all text-slate-600" type="date"/>
            </div>
            @if($search || $tanggal || $status)
                <button wire:click="resetFilters" class="p-3 text-red-500 hover:bg-red-50 rounded-xl transition-all" title="Reset Filter">
                    <span class="material-symbols-outlined">filter_alt_off</span>
                </button>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest w-16 text-center">No</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Pengunjung</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tujuan</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">No. HP/Kontak</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal & Waktu</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($bukutamu as $index => $tamu)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-4 text-sm text-slate-500 text-center font-medium">
                            {{ $bukutamu->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-lavender flex items-center justify-center text-primary font-bold text-xs uppercase">
                                    {{ substr($tamu->nama, 0, 2) }}
                                </div>
                                <span class="text-sm font-bold text-slate-700">{{ $tamu->nama }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-slate-600">{{ $tamu->tujuan_kunjungan }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-slate-600 font-medium">{{ $tamu->telepon }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-700">{{ $tamu->tanggal_kunjungan->format('d M Y') }}</span>
                                <span class="text-[10px] text-slate-400 uppercase font-black">{{ $tamu->jam_kunjungan }} WIB</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.bukutamu.show', $tamu) }}" class="p-2 text-slate-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all" title="Show">
                                    <span class="material-symbols-outlined text-xl">visibility</span>
                                </a>
                                <a href="{{ route('admin.bukutamu.edit', $tamu) }}" class="p-2 text-slate-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-all" title="Edit">
                                    <span class="material-symbols-outlined text-xl">edit_square</span>
                                </a>
                                <button type="button" onclick="confirmDeleteBukuTamu({{ $tamu->id }})" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                    <span class="material-symbols-outlined text-xl">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-400 italic">
                            Belum ada data kunjungan yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-8 py-5 flex items-center justify-between border-t border-slate-50">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                Showing {{ $bukutamu->firstItem() ?? 0 }} to {{ $bukutamu->lastItem() ?? 0 }} of {{ $bukutamu->total() }} entries
            </p>
            <div>
                {{ $bukutamu->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Kunjungan Hari Ini</span>
                <span class="material-symbols-outlined text-primary">groups</span>
            </div>
            <div class="flex items-baseline gap-2">
                <h3 class="text-2xl font-bold text-slate-800">{{ $stats['total_today'] }}</h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kunjungan Minggu Ini</span>
                <span class="material-symbols-outlined text-primary">calendar_view_week</span>
            </div>
            <div class="flex items-baseline gap-2">
                <h3 class="text-2xl font-bold text-slate-800">{{ $stats['weekly'] }}</h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tujuan Terbanyak</span>
                <span class="material-symbols-outlined text-primary">analytics</span>
            </div>
            <div class="flex items-baseline gap-2">
                <h3 class="text-xl font-bold text-slate-800">Umum</h3>
            </div>
        </div>
    </div>
</div>

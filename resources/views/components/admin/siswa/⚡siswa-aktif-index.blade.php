<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\DB;

new class extends Component
{
    use WithPagination;

    public $search = '';
    public $kelompok = '';
    public $sort = 'terbaru';
    public $selectedIds = [];
    public $selectAll = false;
    public $page = 1;

    protected $queryString = [
        'search' => ['except' => '', 'history' => false],
        'kelompok' => ['except' => '', 'history' => false],
        'sort' => ['except' => 'terbaru', 'history' => false],
        'page' => ['except' => 1],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedKelompok()
    {
        $this->resetPage();
    }

    public function updatedSort()
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedIds = $this->getSiswasQuery()->pluck('id')->map(fn($id) => (string)$id)->toArray();
        } else {
            $this->selectedIds = [];
        }
    }

    public function updatedSelectedIds()
    {
        $count = $this->getSiswasQuery()->count();
        $this->selectAll = count($this->selectedIds) === $count && $count > 0;
    }

    public function getSiswasQuery()
    {
        $query = Siswa::with(['tahunAjaran'])->aktif();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama_lengkap', 'LIKE', '%' . $this->search . '%')
                  ->orWhere('nis', 'LIKE', '%' . $this->search . '%')
                  ->orWhere('nik', 'LIKE', '%' . $this->search . '%');
            });
        }

        if ($this->kelompok) {
            $query->where('kelompok', $this->kelompok);
        }

        switch ($this->sort) {
            case 'nama_asc': $query->orderBy('nama_lengkap', 'asc'); break;
            case 'nama_desc': $query->orderBy('nama_lengkap', 'desc'); break;
            case 'terbaru': $query->orderBy('created_at', 'desc'); break;
            case 'terlama': $query->orderBy('created_at', 'asc'); break;
            default: $query->orderBy('created_at', 'desc'); break;
        }

        return $query;
    }

    public function resetFilters()
    {
        $this->reset(['search', 'kelompok', 'sort', 'selectedIds', 'selectAll']);
        $this->resetPage();
    }

    public function deleteSelected()
    {
        if (empty($this->selectedIds)) return;

        $count = count($this->selectedIds);
        Siswa::whereIn('id', $this->selectedIds)->delete();
        
        $this->selectedIds = [];
        $this->selectAll = false;
        
        $this->dispatch('swal:alert', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => $count . ' data siswa berhasil dihapus.'
        ]);
    }

    public function updateStatusSelected($status)
    {
        if (empty($this->selectedIds)) return;

        $updateData = ['status_siswa' => $status];
        if ($status === 'lulus' || $status === 'pindah') {
            $updateData['tanggal_keluar'] = now()->toDateString();
        }

        Siswa::whereIn('id', $this->selectedIds)->update($updateData);

        $count = count($this->selectedIds);
        $this->selectedIds = [];
        $this->selectAll = false;

        $this->dispatch('swal:alert', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => "{$count} data siswa berhasil diupdate ke status {$status}."
        ]);

        if ($status === 'lulus') {
            return redirect()->route('admin.siswa.siswa-lulus.index');
        }
    }

    public function deleteSiswa($id)
    {
        Siswa::find($id)->delete();
        $this->dispatch('swal:alert', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Data siswa berhasil dihapus.'
        ]);
    }

    public function with()
    {
        return [
            'siswas' => $this->getSiswasQuery()->paginate(15),
            'stats' => [
                'total' => Siswa::aktif()->count(),
                'kelompok_a' => Siswa::aktif()->where('kelompok', 'A')->count(),
                'kelompok_b' => Siswa::aktif()->where('kelompok', 'B')->count(),
                'laki_laki' => Siswa::aktif()->where('jenis_kelamin', 'L')->count(),
                'perempuan' => Siswa::aktif()->where('jenis_kelamin', 'P')->count(),
            ]
        ];
    }
};
?>

<div>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-100 dark:border-slate-700 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Aktif</p>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mt-1">{{ $stats['total'] }}</h3>
                </div>
                <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary">groups</span>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-100 dark:border-slate-700 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kelompok A/B</p>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mt-1">{{ $stats['kelompok_a'] }}/{{ $stats['kelompok_b'] }}</h3>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-blue-500">class</span>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-100 dark:border-slate-700 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Laki-Laki</p>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mt-1">{{ $stats['laki_laki'] }}</h3>
                </div>
                <div class="w-10 h-10 rounded-lg bg-cyan-500/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-cyan-500">male</span>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-100 dark:border-slate-700 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Perempuan</p>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mt-1">{{ $stats['perempuan'] }}</h3>
                </div>
                <div class="w-10 h-10 rounded-lg bg-pink-500/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-pink-500">female</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white dark:bg-slate-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 mb-6 border border-slate-100 dark:border-slate-700 shadow-sm flex flex-wrap items-center gap-3 sm:gap-4">
        <!-- Search Input -->
        <div class="flex-1 min-w-[200px] sm:min-w-[250px] relative">
            <span class="material-symbols-outlined absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg sm:text-xl">search</span>
            <input type="text" 
                wire:model.live.debounce.300ms="search"
                class="w-full pl-9 sm:pl-12 pr-3 sm:pr-4 py-2 sm:py-3 bg-slate-50 dark:bg-slate-700 border-none rounded-lg sm:rounded-xl focus:ring-2 focus:ring-primary/20 text-xs sm:text-sm transition-all" 
                placeholder="Cari NIS atau Nama Lengkap..."/>
        </div>

        <!-- Filter Kelompok -->
        <div class="w-full sm:w-40 md:w-48">
            <select wire:model.live="kelompok" class="w-full px-3 sm:px-4 py-2 sm:py-3 bg-slate-50 dark:bg-slate-700 border-none rounded-lg sm:rounded-xl focus:ring-2 focus:ring-primary/20 text-xs sm:text-sm text-slate-600 dark:text-slate-300 transition-all cursor-pointer">
                <option value="">Semua Kelompok</option>
                <option value="A">Kelompok A</option>
                <option value="B">Kelompok B</option>
            </select>
        </div>

        <!-- Sort By -->
        <div class="w-full sm:w-40 md:w-48">
            <select wire:model.live="sort" class="w-full px-3 sm:px-4 py-2 sm:py-3 bg-slate-50 dark:bg-slate-700 border-none rounded-lg sm:rounded-xl focus:ring-2 focus:ring-primary/20 text-xs sm:text-sm text-slate-600 dark:text-slate-300 transition-all cursor-pointer">
                <option value="terbaru">Terbaru</option>
                <option value="terlama">Terlama</option>
                <option value="nama_asc">Nama A-Z</option>
                <option value="nama_desc">Nama Z-A</option>
            </select>
        </div>

        <!-- Bulk Actions -->
        @if(count($selectedIds) > 0)
        <div class="w-full sm:w-48 md:w-56" x-data="{ open: false }">
            <div class="relative">
                <button @click="open = !open" class="w-full px-3 sm:px-4 py-2 sm:py-3 bg-primary text-white rounded-lg sm:rounded-xl font-bold text-xs sm:text-sm flex items-center justify-between transition-all">
                    <span>Aksi ({{ count($selectedIds) }})</span>
                    <span class="material-symbols-outlined text-sm">expand_more</span>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute z-10 w-full mt-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-xl overflow-hidden">
                    <button @click="if(confirm('Hapus {{ count($selectedIds) }} siswa?')) { $wire.deleteSelected(); open = false; }" class="w-full px-4 py-2 text-left text-xs sm:text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">delete</span> Hapus Terpilih
                    </button>
                    <hr class="border-slate-100 dark:border-slate-700">
                    <button @click="if(confirm('Set status LULUS untuk {{ count($selectedIds) }} siswa?')) { $wire.updateStatusSelected('lulus'); open = false; }" class="w-full px-4 py-2 text-left text-xs sm:text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">school</span> Set Lulus
                    </button>
                    <button @click="if(confirm('Set status PINDAH untuk {{ count($selectedIds) }} siswa?')) { $wire.updateStatusSelected('pindah'); open = false; }" class="w-full px-4 py-2 text-left text-xs sm:text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">logout</span> Set Pindah
                    </button>
                </div>
            </div>
        </div>
        @endif

        <!-- Reset Button -->
        <button wire:click="resetFilters" 
                class="px-4 sm:px-6 py-2 sm:py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-lg sm:rounded-xl font-bold text-xs sm:text-sm hover:bg-slate-200 dark:hover:bg-slate-600 transition-all whitespace-nowrap">
            Reset Filter
        </button>
    </div>

    <!-- Table Container -->
    <div class="bg-white dark:bg-slate-800 rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden relative">
        <div wire:loading.delay class="absolute inset-0 bg-white/20 dark:bg-slate-800/20 z-10 flex items-center justify-center">
            <div class="bg-white dark:bg-slate-800 px-4 py-2 rounded-full shadow-lg flex items-center gap-2 border border-slate-100 dark:border-slate-700">
                <span class="material-symbols-outlined animate-spin text-primary text-xl">progress_activity</span>
                <span class="text-xs font-bold text-slate-600 dark:text-slate-300">Memproses...</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-700/50 border-b border-slate-100 dark:border-slate-700">
                        <th class="pl-4 sm:pl-6 py-3 sm:py-4 w-10 sm:w-12">
                            <input type="checkbox" wire:model.live="selectAll" class="w-3.5 h-3.5 sm:w-4 sm:h-4 rounded border-slate-300 text-primary focus:ring-primary transition-all cursor-pointer">
                        </th>
                        <th class="px-2 sm:px-4 py-3 sm:py-4 text-[10px] sm:text-[11px] font-black text-slate-400 uppercase tracking-wider w-12 sm:w-16 text-center">No</th>
                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-[10px] sm:text-[11px] font-black text-slate-400 uppercase tracking-wider">NIS</th>
                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-[10px] sm:text-[11px] font-black text-slate-400 uppercase tracking-wider">Nama Lengkap</th>
                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-[10px] sm:text-[11px] font-black text-slate-400 uppercase tracking-wider hidden md:table-cell">Jenis Kelamin</th>
                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-[10px] sm:text-[11px] font-black text-slate-400 uppercase tracking-wider">Kelompok</th>
                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-[10px] sm:text-[11px] font-black text-slate-400 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                    @forelse($siswas as $index => $siswa)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/50 transition-colors group">
                        <td class="pl-4 sm:pl-6 py-3 sm:py-4">
                            <input type="checkbox" wire:model.live="selectedIds" value="{{ $siswa->id }}" class="w-3.5 h-3.5 sm:w-4 sm:h-4 rounded border-slate-300 text-primary focus:ring-primary transition-all cursor-pointer">
                        </td>
                        <td class="px-2 sm:px-4 py-3 sm:py-4 text-xs sm:text-sm font-medium text-slate-400 text-center">
                            {{ $siswas->firstItem() + $index }}
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-medium text-slate-600 dark:text-slate-300">
                            {{ $siswa->nis ?? 'NIS-' . str_pad($siswa->id, 4, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-lavender dark:bg-primary/20 text-primary dark:text-white flex items-center justify-center font-bold text-[10px] sm:text-xs">
                                    {{ strtoupper(substr($siswa->nama_lengkap ?? $siswa->name ?? 'NA', 0, 2)) }}
                                </div>
                                <span class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white truncate max-w-[100px] sm:max-w-[150px] md:max-w-[200px]">
                                    {{ $siswa->nama_lengkap ?? $siswa->name ?? 'Nama Siswa' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm text-slate-600 dark:text-slate-300 hidden md:table-cell">
                            {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4">
                            <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-bold 
                                {{ $siswa->kelompok == 'A' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-purple-100 text-primary dark:bg-purple-900/30 dark:text-purple-400' }}">
                                Kelompok {{ $siswa->kelompok }}
                            </span>
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4">
                            <div class="flex items-center justify-center gap-0.5 sm:gap-1">
                                <a href="{{ route('admin.siswa.siswa-aktif.show', $siswa->id) }}" 
                                   class="p-1 sm:p-2 bg-slate-50 dark:bg-slate-700 hover:bg-primary/10 text-slate-400 hover:text-primary rounded-lg transition-all" 
                                   title="Detail">
                                    <span class="material-symbols-outlined text-sm sm:text-lg">visibility</span>
                                </a>
                                <a href="{{ route('admin.siswa.siswa-aktif.edit', $siswa->id) }}" 
                                   class="p-1 sm:p-2 bg-slate-50 dark:bg-slate-700 hover:bg-primary/10 text-slate-400 hover:text-primary rounded-lg transition-all" 
                                   title="Edit">
                                    <span class="material-symbols-outlined text-sm sm:text-lg">edit</span>
                                </a>
                                <button type="button" 
                                        @click="if(confirm('Hapus data siswa ini?')) { $wire.deleteSiswa({{ $siswa->id }}) }"
                                        class="p-1 sm:p-2 bg-slate-50 dark:bg-slate-700 hover:bg-red-50 dark:hover:bg-red-900/20 text-slate-400 hover:text-red-500 rounded-lg transition-all" 
                                        title="Hapus">
                                    <span class="material-symbols-outlined text-sm sm:text-lg">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 sm:py-12 text-center">
                            <div class="flex flex-col items-center">
                                <span class="material-symbols-outlined text-4xl sm:text-5xl text-slate-300 mb-3">inbox</span>
                                <p class="text-sm sm:text-base text-slate-500">Tidak ada data siswa ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-slate-50 dark:border-slate-700">
            {{ $siswas->links() }}
        </div>
    </div>
</div>
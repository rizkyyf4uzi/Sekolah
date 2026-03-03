<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Siswa;

new class extends Component
{
    use WithPagination;

    public $tahun;
    public $search = '';
    public $page = 1;

    protected $queryString = [
        'search' => ['except' => '', 'history' => false],
        'page' => ['except' => 1],
    ];

    public function mount($tahun)
    {
        $this->tahun = $tahun;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search']);
        $this->resetPage();
    }

    public function getSiswasQuery()
    {
        $query = Siswa::lulus()
            ->where('tahun_ajaran', $this->tahun);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama_lengkap', 'LIKE', '%' . $this->search . '%')
                  ->orWhere('nis', 'LIKE', '%' . $this->search . '%');
            });
        }

        return $query->orderBy('nama_lengkap', 'asc');
    }

    public function with()
    {
        return [
            'siswas' => $this->getSiswasQuery()->paginate(15)
        ];
    }
};
?>

<div>
    <!-- Filter Section -->
    <div class="bg-white dark:bg-slate-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 mb-6 border border-slate-100 dark:border-slate-700 shadow-sm flex flex-wrap items-center gap-3">
        <div class="flex-1 min-w-[200px] relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
            <input type="text" 
                   wire:model.live.debounce.300ms="search"
                   class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-slate-700 border-none rounded-lg focus:ring-2 focus:ring-primary/20 text-sm transition-all" 
                   placeholder="Cari nama atau NIS siswa..."/>
        </div>
        <button wire:click="resetFilters" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-lg font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-600 transition-all">
            Reset
        </button>
    </div>

    <!-- Table Container -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden relative">
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
                        <th class="px-4 py-3 text-[11px] font-black text-slate-400 uppercase tracking-wider w-12 text-center">No</th>
                        <th class="px-4 py-3 text-[11px] font-black text-slate-400 uppercase tracking-wider">NIS</th>
                        <th class="px-4 py-3 text-[11px] font-black text-slate-400 uppercase tracking-wider">Nama Lengkap</th>
                        <th class="px-4 py-3 text-[11px] font-black text-slate-400 uppercase tracking-wider hidden md:table-cell">Jenis Kelamin</th>
                        <th class="px-4 py-3 text-[11px] font-black text-slate-400 uppercase tracking-wider">Kelompok</th>
                        <th class="px-4 py-3 text-[11px] font-black text-slate-400 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                    @forelse($siswas as $index => $siswa)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-slate-400 text-center">
                            {{ $siswas->firstItem() + $index }}
                        </td>
                        <td class="px-4 py-3 text-sm font-medium text-slate-600 dark:text-slate-300">
                            {{ $siswa->nis ?? 'NIS-' . str_pad($siswa->id, 4, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-lavender dark:bg-primary/20 text-primary dark:text-white flex items-center justify-center font-bold text-xs">
                                    {{ strtoupper(substr($siswa->nama_lengkap, 0, 2)) }}
                                </div>
                                <span class="text-sm font-bold text-slate-800 dark:text-white">
                                    {{ $siswa->nama_lengkap }}
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300 hidden md:table-cell">
                            {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $siswa->kelompok == 'A' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-purple-100 text-primary dark:bg-purple-900/30 dark:text-purple-400' }}">
                                Kelompok {{ $siswa->kelompok }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center">
                                <a href="{{ route('admin.siswa.siswa-lulus.show', $siswa->id) }}" 
                                   class="p-2 bg-slate-50 dark:bg-slate-700 hover:bg-primary/10 text-slate-400 hover:text-primary rounded-lg transition-all"
                                   title="Detail">
                                    <span class="material-symbols-outlined text-lg">visibility</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center">
                            <div class="flex flex-col items-center">
                                <span class="material-symbols-outlined text-5xl text-slate-300 mb-3">inbox</span>
                                <p class="text-base text-slate-500">Tidak ada data siswa</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 border-t border-slate-50 dark:border-slate-700">
            {{ $siswas->links() }}
        </div>
    </div>
</div>
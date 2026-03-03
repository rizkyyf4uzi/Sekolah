<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

new class extends Component
{
    use WithPagination;

    public $search = '';
    public $sort = 'newest';
    public $page = 1;

    protected $queryString = [
        'search' => ['except' => '', 'history' => false],
        'sort' => ['except' => 'newest', 'history' => false],
        'page' => ['except' => 1],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSort()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'sort']);
        $this->resetPage();
    }

    public function getRekapQuery()
    {
        $query = Siswa::lulus()
            ->select('tahun_ajaran', DB::raw('count(*) as jumlah_siswa'))
            ->groupBy('tahun_ajaran');

        if ($this->search) {
            $query->where('tahun_ajaran', 'LIKE', '%' . $this->search . '%');
        }

        switch ($this->sort) {
            case 'newest': $query->orderBy('tahun_ajaran', 'desc'); break;
            case 'oldest': $query->orderBy('tahun_ajaran', 'asc'); break;
            case 'most': $query->orderBy('jumlah_siswa', 'desc'); break;
            case 'least': $query->orderBy('jumlah_siswa', 'asc'); break;
            default: $query->orderBy('tahun_ajaran', 'desc'); break;
        }

        return $query;
    }

    public function with()
    {
        return [
            'rekapLulus' => $this->getRekapQuery()->paginate(10)
        ];
    }
};
?>

<div>
    <!-- Filter Section -->
    <div class="bg-white dark:bg-slate-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 mb-6 sm:mb-8 border border-slate-100 dark:border-slate-700 shadow-sm flex flex-wrap items-center gap-3 sm:gap-4">
        <!-- Search Input -->
        <div class="flex-1 min-w-[200px] sm:min-w-[250px] relative">
            <span class="material-symbols-outlined absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg sm:text-xl">search</span>
            <input type="text" 
                   wire:model.live.debounce.300ms="search"
                   class="w-full pl-9 sm:pl-12 pr-3 sm:pr-4 py-2 sm:py-3 bg-slate-50 dark:bg-slate-700 border-none rounded-lg sm:rounded-xl focus:ring-2 focus:ring-primary/20 text-xs sm:text-sm transition-all" 
                   placeholder="Cari Tahun Ajaran..."/>
        </div>

        <!-- Sort By -->
        <div class="w-full sm:w-40 md:w-48">
            <select wire:model.live="sort" class="w-full px-3 sm:px-4 py-2 sm:py-3 bg-slate-50 dark:bg-slate-700 border-none rounded-lg sm:rounded-xl focus:ring-2 focus:ring-primary/20 text-xs sm:text-sm text-slate-600 dark:text-slate-300 transition-all cursor-pointer">
                <option value="newest">Terbaru</option>
                <option value="oldest">Terlama</option>
                <option value="most">Paling Banyak</option>
                <option value="least">Paling Sedikit</option>
            </select>
        </div>

        <!-- Reset Filter Button -->
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
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-[10px] sm:text-[11px] font-black text-slate-400 uppercase tracking-wider w-12 sm:w-16 text-center">No</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-[10px] sm:text-[11px] font-black text-slate-400 uppercase tracking-wider">Tahun Ajaran</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-[10px] sm:text-[11px] font-black text-slate-400 uppercase tracking-wider">Jumlah Siswa Lulus</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-[10px] sm:text-[11px] font-black text-slate-400 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                    @forelse($rekapLulus as $index => $item)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/50 transition-colors group">
                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-medium text-slate-400 text-center">
                            {{ $rekapLulus->firstItem() + $index }}
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-primary/10 dark:bg-primary/20 text-primary flex items-center justify-center">
                                    <span class="material-symbols-outlined text-base sm:text-xl">calendar_today</span>
                                </div>
                                <span class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white">
                                    {{ $item->tahun_ajaran }}
                                </span>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                            <span class="inline-flex items-center px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                {{ number_format($item->jumlah_siswa) }} Siswa
                            </span>
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                            <div class="flex items-center justify-center">
                                <a href="{{ route('admin.siswa.siswa-lulus.by-tahun', ['tahun' => $item->tahun_ajaran]) }}" 
                                   class="p-2 sm:p-2.5 bg-slate-50 dark:bg-slate-700 hover:bg-primary/10 text-slate-400 hover:text-primary rounded-lg sm:rounded-xl transition-all group/btn" 
                                   title="Lihat Daftar Siswa">
                                    <span class="material-symbols-outlined text-base sm:text-xl">visibility</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 sm:py-12 text-center">
                            <div class="flex flex-col items-center">
                                <span class="material-symbols-outlined text-4xl sm:text-5xl text-slate-300 mb-3">inbox</span>
                                <p class="text-sm sm:text-base text-slate-500">Belum ada data siswa lulus</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-slate-50 dark:border-slate-700">
            {{ $rekapLulus->links() }}
        </div>
    </div>
</div>
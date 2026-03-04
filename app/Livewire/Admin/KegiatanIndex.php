<?php

namespace App\Livewire\Admin;

use App\Models\Kegiatan;
use Livewire\Component;
use Livewire\WithPagination;

class KegiatanIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $kategori = '';
    public $status = '';
    public $dateRange = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'kategori' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'kategori', 'status', 'dateRange']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Kegiatan::with('user')->latest();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama_kegiatan', 'like', "%{$this->search}%")
                  ->orWhere('lokasi', 'like', "%{$this->search}%");
            });
        }

        if ($this->kategori) {
            $query->where('kategori', $this->kategori);
        }

        if ($this->status) {
            $query->where('is_published', $this->status == 'published');
        }

        // Logic for date range could be added here if needed, 
        // for now keeping it simple as per search/category pattern

        $kegiatans = $query->paginate(10);
        $categories = Kegiatan::select('kategori')->distinct()->pluck('kategori');

        return view('livewire.admin.kegiatan-index', [
            'kegiatans' => $kegiatans,
            'categories' => $categories
        ]);
    }
}

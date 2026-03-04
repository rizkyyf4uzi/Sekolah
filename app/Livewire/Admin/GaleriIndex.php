<?php

namespace App\Livewire\Admin;

use App\Models\Galeri;
use Livewire\Component;
use Livewire\WithPagination;

class GaleriIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $kategori = '';
    public $status = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'kategori' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingKategori()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'kategori', 'status']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Galeri::with(['user', 'gambar'])->latest();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('judul', 'like', "%{$this->search}%")
                  ->orWhere('deskripsi', 'like', "%{$this->search}%")
                  ->orWhere('kategori', 'like', "%{$this->search}%");
            });
        }

        if ($this->kategori) {
            $query->where('kategori', $this->kategori);
        }

        if ($this->status) {
            $query->where('is_published', $this->status == 'published');
        }

        $galeri = $query->paginate(20);
        $kategoriList = Galeri::select('kategori')->distinct()->pluck('kategori');

        return view('livewire.admin.galeri-index', [
            'galeri' => $galeri,
            'kategoriList' => $kategoriList,
        ]);
    }
}

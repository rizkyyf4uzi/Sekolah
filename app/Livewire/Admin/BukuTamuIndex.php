<?php

namespace App\Livewire\Admin;

use App\Models\BukuTamu;
use Livewire\Component;
use Livewire\WithPagination;

class BukuTamuIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $tanggal = '';
    public $status = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'tanggal' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTanggal()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'tanggal', 'status']);
        $this->resetPage();
    }

    public function render()
    {
        $query = BukuTamu::latest();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('instansi', 'like', '%' . $this->search . '%')
                  ->orWhere('tujuan_kunjungan', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->tanggal) {
            $query->whereDate('tanggal_kunjungan', $this->tanggal);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $bukutamu = $query->paginate(10);

        $stats = [
            'total_today' => BukuTamu::whereDate('created_at', today())->count(),
            'weekly' => BukuTamu::where('created_at', '>=', now()->startOfWeek())->count(),
        ];

        return view('livewire.admin.buku-tamu-index', [
            'bukutamu' => $bukutamu,
            'stats' => $stats
        ]);
    }
}

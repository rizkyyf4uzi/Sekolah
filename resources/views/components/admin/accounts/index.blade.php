<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

new class extends Component
{
    use WithPagination;

    public $search = '';
    public $role = '';
    public $status = '';
    public $sort = 'terbaru';

    protected $queryString = [
        'search' => ['except' => '', 'history' => false],
        'role' => ['except' => '', 'history' => false],
        'status' => ['except' => '', 'history' => false],
        'sort' => ['except' => 'terbaru', 'history' => false],
        'page' => ['except' => 1],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedRole()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function updatedSort()
    {
        $this->resetPage();
    }

    public function getUsersProperty()
    {
        $query = User::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'LIKE', '%' . $this->search . '%')
                  ->orWhere('email', 'LIKE', '%' . $this->search . '%');
            });
        }

        if ($this->role) {
            $query->where('role', $this->role);
        }

        if ($this->status && Schema::hasColumn('users', 'is_active')) {
            $isActive = $this->status === 'active';
            $query->where('is_active', $isActive);
        }

        switch ($this->sort) {
            case 'nama_asc': $query->orderBy('name', 'asc'); break;
            case 'nama_desc': $query->orderBy('name', 'desc'); break;
            case 'login_terbaru': $query->orderBy('last_login_at', 'desc'); break;
            case 'login_terlama': $query->orderBy('last_login_at', 'asc'); break;
            default: $query->orderBy('created_at', 'desc'); break;
        }

        return $query->paginate(15);
    }

    public function getStatsProperty()
    {
        return [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
            'admin' => User::where('role', 'admin')->count(),
            'kepala_sekolah' => User::where('role', 'kepala_sekolah')->count(),
            'operator' => User::where('role', 'operator')->count(),
            'guru' => User::where('role', 'guru')->count(),
        ];
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->role = '';
        $this->status = '';
        $this->sort = 'terbaru';
        $this->resetPage();
    }
}
?>

<div>
<style>
    .status-badge {
        padding: 0.25rem 0.625rem;
        border-radius: 0.5rem;
        font-size: 0.625rem;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-block;
        white-space: nowrap;
    }
    .status-active {
        background: #dcfce7;
        color: #16a34a;
    }
    .status-inactive {
        background: #fee2e2;
        color: #dc2626;
    }
    .role-badge {
        padding: 0.25rem 0.625rem;
        border-radius: 0.5rem;
        font-size: 0.625rem;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-block;
        white-space: nowrap;
    }
    .role-admin { background: #f3e8ff; color: #7c3aed; }
    .role-kepala_sekolah { background: #dbeafe; color: #2563eb; }
    .role-operator { background: #fed7aa; color: #ea580c; }
    .role-guru { background: #d1fae5; color: #059669; }
    
    .action-btn {
        padding: 0.375rem;
        border-radius: 0.5rem;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
    }
    
    .self-account {
        background-color: #f0f9ff;
    }

    [wire\:loading] {
        opacity: 0.5;
        pointer-events: none;
    }
</style>

<div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="p-6 border-b border-slate-50">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-4">
                <div class="relative w-full sm:w-72">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">search</span>
                    <input type="text" 
                           wire:model.live.debounce.300ms="search"
                           placeholder="Cari nama atau email..." 
                           class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 text-sm transition-all">
                </div>
                <div class="flex items-center gap-3">
                    <select wire:model.live="role" class="bg-slate-50 border-none rounded-xl text-xs font-semibold text-slate-600 focus:ring-2 focus:ring-primary/20 py-2.5 px-4 pr-10 cursor-pointer">
                        <option value="">Semua Role</option>
                        <option value="admin">Admin</option>
                        <option value="kepala_sekolah">Kepala Sekolah</option>
                        <option value="operator">Operator</option>
                        <option value="guru">Guru</option>
                    </select>
                    <select wire:model.live="status" class="bg-slate-50 border-none rounded-xl text-xs font-semibold text-slate-600 focus:ring-2 focus:ring-primary/20 py-2.5 px-4 pr-10 cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Nonaktif</option>
                    </select>
                    @if($search || $role || $status)
                        <button wire:click="resetFilters" class="text-xs text-slate-500 hover:text-primary">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    @endif
                </div>
            </div>
            <a href="{{ route('admin.accounts.create') }}" class="flex items-center justify-center gap-2 px-6 py-2.5 bg-primary text-white rounded-xl font-bold text-sm hover:opacity-90 transition-all shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-lg">add</span>
                Tambah User
            </a>
        </div>
    </div>

    <div class="overflow-x-auto" wire:loading.class.opacity-50 wire:target="search,role,status,sort">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest">No</th>
                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest">Nama</th>
                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest">Email</th>
                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest">Role</th>
                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest">Terakhir Login</th>
                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($this->users as $index => $user)
                <tr class="hover:bg-slate-50/50 transition-colors {{ $user->id === auth()->id() ? 'self-account' : '' }}">
                    <td class="px-6 py-4 text-sm text-slate-500">
                        {{ $loop->iteration + ($this->users->currentPage() - 1) * $this->users->perPage() }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($user->foto)
                                <img class="w-8 h-8 rounded-full object-cover" src="{{ asset('storage/'.$user->foto) }}" alt="">
                            @else
                                <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                            @endif
                            <span class="text-sm font-bold text-slate-700">
                                {{ $user->name }}
                                @if($user->id === auth()->id())
                                    <span class="text-xs text-blue-600 font-normal">(Anda)</span>
                                @endif
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ $user->email }}
                        @if($user->email_verified_at)
                            <span class="material-symbols-outlined text-green-500 text-xs ml-1">verified</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="role-badge role-{{ $user->role }}">
                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="flex items-center gap-1.5 text-[10px] font-bold {{ $user->is_active ? 'text-green-600' : 'text-red-600' }} uppercase">
                            <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-500">
                        @if($user->last_login_at)
                            {{ \Carbon\Carbon::parse($user->last_login_at)->format('d M Y, H:i') }}
                        @else
                            <span class="text-slate-400">Belum pernah</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.accounts.show', $user) }}" 
                               class="action-btn text-slate-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all" 
                               title="Detail">
                                <span class="material-symbols-outlined text-xl">visibility</span>
                            </a>
                            
                            <a href="{{ route('admin.accounts.edit', $user) }}" 
                               class="action-btn text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all" 
                               title="Edit">
                                <span class="material-symbols-outlined text-xl">edit</span>
                            </a>
                            
                            <button onclick="resetPassword({{ $user->id }}, '{{ $user->name }}')" 
                                    class="action-btn text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" 
                                    title="Reset Password">
                                <span class="material-symbols-outlined text-xl">key</span>
                            </button>
                            
                            @if($user->id !== auth()->id())
                                <button onclick="toggleStatus({{ $user->id }}, '{{ $user->name }}', {{ $user->is_active ? 0 : 1 }})" 
                                        class="action-btn text-slate-400 hover:{{ $user->is_active ? 'text-red-600 hover:bg-red-50' : 'text-green-600 hover:bg-green-50' }} rounded-lg transition-all"
                                        title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <span class="material-symbols-outlined text-xl">{{ $user->is_active ? 'block' : 'check_circle' }}</span>
                                </button>
                                
                                <button onclick="deleteAccount({{ $user->id }}, '{{ $user->name }}')" 
                                        class="action-btn text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" 
                                        title="Hapus">
                                    <span class="material-symbols-outlined text-xl">delete</span>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                        <span class="material-symbols-outlined text-5xl mb-4 text-slate-300">person_off</span>
                        <p class="text-lg font-medium">Tidak ada user ditemukan</p>
                        <p class="text-sm text-slate-400 mt-1">Coba ubah filter atau tambah user baru</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($this->users->hasPages())
    <div class="p-6 border-t border-slate-50 flex items-center justify-between">
        <p class="text-xs text-slate-400 font-medium">
            Showing <span class="text-slate-700">{{ $this->users->firstItem() }}</span> to <span class="text-slate-700">{{ $this->users->lastItem() }}</span> of <span class="text-slate-700">{{ $this->users->total() }}</span> users
        </p>
        <div class="flex items-center gap-2">
            {{ $this->users->links('vendor.pagination.tailwind') }}
        </div>
    </div>
    @endif
</div>

<!-- Hidden Forms -->
<div hidden>
@foreach($this->users as $user)
    <form id="reset-password-form-{{ $user->id }}" action="{{ route('admin.accounts.reset-password', $user) }}" method="POST">
        @csrf
        <input type="hidden" name="new_password" value="password123">
        <input type="hidden" name="new_password_confirmation" value="password123">
    </form>
    
    <form id="toggle-form-{{ $user->id }}" action="{{ route('admin.accounts.toggle-status', $user) }}" method="POST">
        @csrf
        @method('PATCH')
    </form>
    
    <form id="delete-form-{{ $user->id }}" action="{{ route('admin.accounts.destroy', $user) }}" method="POST">
        @csrf
        @method('DELETE')
    </form>
@endforeach
</div>

<!-- Reset Password Modal -->
<div id="resetPasswordModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4 shadow-xl">
        <h3 class="text-lg font-bold text-slate-800 mb-2">Reset Password</h3>
        <p class="text-sm text-slate-600 mb-4">Password untuk akun <span id="resetUserName" class="font-semibold text-primary"></span> akan direset menjadi:</p>
        <div class="bg-slate-50 p-3 rounded-xl text-center mb-6">
            <code class="text-lg font-mono font-bold text-primary">password123</code>
        </div>
        <div class="flex justify-end gap-3">
            <button type="button" onclick="closeResetModal()" 
                    class="px-4 py-2.5 border border-slate-200 rounded-xl text-slate-700 hover:bg-slate-50 transition-colors font-medium text-sm">
                Batal
            </button>
            <button type="button" onclick="confirmReset()" 
                    class="px-4 py-2.5 bg-primary text-white rounded-xl hover:opacity-90 transition-colors font-medium text-sm">
                Reset Password
            </button>
        </div>
    </div>
</div>

<script>
    let selectedUserId = null;
    let selectedUserName = '';

    function resetPassword(userId, userName) {
        selectedUserId = userId;
        selectedUserName = userName;
        document.getElementById('resetUserName').textContent = userName;
        document.getElementById('resetPasswordModal').classList.remove('hidden');
        document.getElementById('resetPasswordModal').classList.add('flex');
    }

    function closeResetModal() {
        document.getElementById('resetPasswordModal').classList.add('hidden');
        document.getElementById('resetPasswordModal').classList.remove('flex');
        selectedUserId = null;
        selectedUserName = '';
    }

    function confirmReset() {
        if (selectedUserId) {
            Swal.fire({
                title: 'Konfirmasi Reset Password',
                text: `Apakah Anda yakin ingin mereset password untuk akun ${selectedUserName}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6B46C1',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, reset!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`reset-password-form-${selectedUserId}`).submit();
                }
                closeResetModal();
            });
        }
    }

    function toggleStatus(userId, userName, activate) {
        const action = activate ? 'mengaktifkan' : 'menonaktifkan';
        Swal.fire({
            title: 'Konfirmasi',
            text: `Apakah Anda yakin ingin ${action} akun ${userName}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#6B46C1',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, lanjutkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`toggle-form-${userId}`).submit();
            }
        });
    }

    function deleteAccount(userId, userName) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: `Apakah Anda yakin ingin menghapus akun ${userName}? Tindakan ini tidak dapat dibatalkan.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${userId}`).submit();
            }
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeResetModal();
        }
    });

    document.getElementById('resetPasswordModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeResetModal();
        }
    });
</script>
</div>


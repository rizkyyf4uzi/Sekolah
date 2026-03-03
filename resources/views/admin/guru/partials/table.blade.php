@if(isset($gurus) && $gurus->count() > 0)
<div class="overflow-x-auto no-scrollbar">
    <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700/50">
        <thead class="bg-slate-50/50 dark:bg-slate-900/50">
            <tr>
                <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hidden sm:table-cell">No</th>
                <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Info Guru</th>
                <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hidden md:table-cell">NIP</th>
                <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Jabatan</th>
                <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hidden lg:table-cell">Kelompok</th>
                <th class="px-6 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-50 dark:divide-slate-700/50">
            @foreach($gurus as $index => $guru)
            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition-colors group">
                <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-slate-400 hidden sm:table-cell">
                    {{ ($gurus->currentPage() - 1) * $gurus->perPage() + $index + 1 }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-black text-sm shadow-sm">
                            {{ strtoupper(substr($guru->nama, 0, 1)) }}
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-black text-slate-900 dark:text-white tracking-tight">{{ $guru->nama }}</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $guru->email ?: 'No Email' }}</span>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                    <span class="px-3 py-1.5 rounded-lg bg-slate-50 dark:bg-slate-900 text-slate-600 dark:text-slate-400 text-[10px] font-black tracking-widest font-mono">
                        {{ $guru->nip ?: 'N/A' }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($guru->jabatan == 'guru')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest">
                            <span class="material-symbols-outlined text-sm">school</span>
                            Guru
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 text-[10px] font-black uppercase tracking-widest">
                            <span class="material-symbols-outlined text-sm">badge</span>
                            Staff
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap hidden lg:table-cell">
                    @if($guru->jabatan == 'guru' && $guru->kelompok)
                        <span class="px-3 py-1.5 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 text-[10px] font-black uppercase tracking-widest">
                            Kelompok {{ $guru->kelompok }}
                        </span>
                    @else
                        <span class="text-slate-300 dark:text-slate-600">--</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right h-full">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.guru.show', $guru->id) }}" 
                           class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all shadow-sm"
                           title="Detail">
                            <span class="material-symbols-outlined text-lg">visibility</span>
                        </a>
                        <a href="{{ route('admin.guru.edit', $guru->id) }}" 
                           class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all shadow-sm"
                           title="Edit">
                            <span class="material-symbols-outlined text-lg">edit</span>
                        </a>
                        <form action="{{ route('admin.guru.destroy', $guru->id) }}" method="POST" class="inline" id="delete-form-{{ $guru->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDeleteGuru({{ $guru->id }}, '{{ $guru->nama }}')"
                                    class="w-9 h-9 rounded-xl bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all shadow-sm"
                                    title="Hapus">
                                <span class="material-symbols-outlined text-lg">delete</span>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="flex flex-col items-center justify-center py-20 px-4 text-center">
    <div class="w-24 h-24 rounded-[2.5rem] bg-slate-50 dark:bg-slate-900/50 flex items-center justify-center text-slate-200 dark:text-slate-700 mb-6">
        <span class="material-symbols-outlined text-5xl">person_off</span>
    </div>
    <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight mb-2">Tidak ada data guru</h3>
    <p class="text-sm text-slate-400 font-bold uppercase tracking-widest max-w-sm">
        @if(isset($search) && !empty($search))
            Tidak ditemukan guru dengan pencarian "{{ $search }}"
        @else
            Belum ada guru yang terdaftar dalam sistem.
        @endif
    </p>
    <div class="mt-8 flex flex-wrap justify-center gap-3">
        <a href="{{ route('admin.guru.create') }}" 
           class="px-6 py-4 rounded-2xl bg-primary text-white font-black text-xs uppercase tracking-widest shadow-xl shadow-primary/20 hover:scale-105 transition-all active:scale-95 flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">add</span> Tambah Guru Pertama
        </a>
        @if(isset($search) && !empty($search))
        <a href="{{ route('admin.guru.index') }}" 
           class="px-6 py-4 rounded-2xl bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-black text-xs uppercase tracking-widest border border-slate-200 dark:border-slate-600 shadow-sm hover:bg-slate-50 transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">refresh</span> Reset Pencarian
        </a>
        @endif
    </div>
</div>
@endif

<script>
function confirmDeleteGuru(id, name) {
    Swal.fire({
        title: 'Hapus Data Guru?',
        html: `Apakah Anda yakin ingin menghapus data guru <b>${name}</b>?<br><small class="text-rose-500 font-bold uppercase mt-2 block tracking-widest">Tindakan ini tidak dapat dibatalkan!</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'YA, HAPUS DATA',
        cancelButtonText: 'BATALKAN',
        background: document.documentElement.classList.contains('dark') ? '#0f172a' : '#ffffff',
        color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a',
        customClass: {
            popup: 'rounded-[2.5rem] border-none shadow-2xl p-8',
            confirmButton: 'rounded-2xl px-8 py-4 font-black text-[10px] tracking-[0.2em]',
            cancelButton: 'rounded-2xl px-8 py-4 font-black text-[10px] tracking-[0.2em] bg-slate-100 text-slate-600',
            title: 'text-2xl font-black'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>
@if(isset($gurus) && $gurus->count() > 0)
<table class="w-full text-left border-collapse">
    <thead>
        <tr class="bg-slate-50/50 border-b border-slate-100">
            <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider w-16">No</th>
            <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider">Nama Lengkap</th>
            <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider hidden md:table-cell">NIP</th>
            <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider">Jabatan</th>
            <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider hidden lg:table-cell">Kelompok</th>
            <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider text-center">Aksi</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-slate-50">
        @foreach($gurus as $index => $guru)
        @php
            $colors = [
                ['bg' => 'bg-primary/10', 'text' => 'text-primary'],
                ['bg' => 'bg-slate-100',  'text' => 'text-slate-600'],
                ['bg' => 'bg-emerald-100','text' => 'text-emerald-600'],
                ['bg' => 'bg-pink-100',   'text' => 'text-pink-600'],
                ['bg' => 'bg-amber-100',  'text' => 'text-amber-600'],
                ['bg' => 'bg-blue-100',   'text' => 'text-blue-600'],
            ];
            $c = $colors[$index % count($colors)];
            $initials = strtoupper(substr($guru->nama, 0, 1));
            $parts = explode(' ', $guru->nama);
            if (count($parts) > 1) $initials .= strtoupper(substr($parts[1], 0, 1));
        @endphp
        <tr class="hover:bg-slate-50/50 transition-colors group">
            <td class="px-6 py-4 text-sm font-medium text-slate-400">
                {{ ($gurus->currentPage() - 1) * $gurus->perPage() + $index + 1 }}
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg {{ $c['bg'] }} {{ $c['text'] }} flex items-center justify-center font-bold text-xs flex-shrink-0">
                        {{ $initials }}
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-bold text-slate-800">{{ $guru->nama }}</span>
                        <span class="text-[10px] text-slate-400">{{ $guru->email ?: '-' }}</span>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 text-sm text-slate-600 hidden md:table-cell">
                {{ $guru->nip ?: '-' }}
            </td>
            <td class="px-6 py-4">
                @if($guru->jabatan == 'guru')
                    <span class="px-3 py-1 bg-purple-100 text-primary rounded-full text-[10px] font-bold uppercase">Guru</span>
                @else
                    <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[10px] font-bold uppercase">Staff</span>
                @endif
            </td>
            <td class="px-6 py-4 text-sm text-slate-600 hidden lg:table-cell">
                @if($guru->jabatan == 'guru' && $guru->kelompok)
                    Kelompok {{ $guru->kelompok }}
                @else
                    <span class="text-slate-300">-</span>
                @endif
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center justify-center gap-2">
                    <a href="{{ route('admin.guru.show', $guru->id) }}"
                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all"
                       title="Detail">
                        <span class="material-symbols-outlined text-lg">visibility</span>
                    </a>
                    <a href="{{ route('admin.guru.edit', $guru->id) }}"
                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition-all"
                       title="Edit">
                        <span class="material-symbols-outlined text-lg">edit</span>
                    </a>
                    <form action="{{ route('admin.guru.destroy', $guru->id) }}" method="POST" class="inline" id="delete-form-{{ $guru->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmDeleteGuru({{ $guru->id }}, '{{ $guru->nama }}')"
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all"
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
@else
<div class="flex flex-col items-center justify-center py-20 px-4 text-center">
    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
        <span class="material-symbols-outlined text-4xl text-slate-300">person_off</span>
    </div>
    <h3 class="text-xl font-bold text-slate-800 mb-2">Tidak Ada Data Guru</h3>
    <p class="text-slate-400 text-sm max-w-sm mx-auto mb-8">
        @if(isset($search) && !empty($search))
            Tidak ditemukan guru dengan pencarian "{{ $search }}"
        @else
            Belum ada guru yang terdaftar dalam sistem.
        @endif
    </p>
    <div class="flex flex-wrap justify-center gap-3">
        <a href="{{ route('admin.guru.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary/90 transition-all shadow-lg shadow-primary/25">
            <span class="material-symbols-outlined text-lg">person_add</span>
            Tambah Guru Pertama
        </a>
        @if(isset($search) && !empty($search))
        <a href="{{ route('admin.guru.index') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-200 transition-all">
            <span class="material-symbols-outlined text-lg">refresh</span>
            Reset Pencarian
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
        customClass: {
            popup: 'rounded-3xl shadow-2xl p-8',
            confirmButton: 'rounded-2xl px-8 py-3 font-black text-xs tracking-widest',
            cancelButton: 'rounded-2xl px-8 py-3 font-black text-xs tracking-widest',
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>
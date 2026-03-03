@extends('layouts.admin')

@section('title', 'Rekap Absensi Guru')

@section('breadcrumb', 'Akademik / Absensi Guru / Rekap')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="p-6 sm:p-8 border-b border-slate-100 flex flex-col sm:flex-row gap-4 sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Rekap Absensi Guru</h2>
            <p class="text-sm text-slate-500 mt-1">Rekap per guru untuk bulan terpilih.</p>
        </div>
        <a href="{{ route('admin.absensi-guru.index') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white text-slate-700 rounded-xl border border-slate-200 hover:bg-slate-50 transition-all font-bold text-sm shadow-sm">
            <span class="material-symbols-outlined text-xl">arrow_back</span>
            Kembali
        </a>
    </div>

    <form method="GET" class="p-6 sm:p-8 border-b border-slate-100 flex flex-col sm:flex-row gap-3 sm:items-end">
        <div class="w-full sm:w-64">
            <label class="block text-sm font-bold text-slate-700 mb-2">Bulan</label>
            <input type="month" name="bulan" value="{{ $bulan }}"
                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm"/>
        </div>
        <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary/90 transition-all shadow-lg shadow-primary/20">
            Tampilkan
        </button>
    </form>

    <div class="p-6 sm:p-8 overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">No</th>
                    <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Guru</th>
                    <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Hadir</th>
                    <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Sakit</th>
                    <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Izin</th>
                    <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Alpa</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($rekap as $g)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-4 py-3 text-sm text-slate-500">{{ $loop->iteration + ($rekap->currentPage()-1)*$rekap->perPage() }}</td>
                    <td class="px-4 py-3">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-slate-800">{{ $g->nama }}</span>
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $g->nip ?? '-' }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-center text-sm font-bold text-green-700">{{ (int) $g->hadir }}</td>
                    <td class="px-4 py-3 text-center text-sm font-bold text-amber-700">{{ (int) $g->sakit }}</td>
                    <td class="px-4 py-3 text-center text-sm font-bold text-blue-700">{{ (int) $g->izin }}</td>
                    <td class="px-4 py-3 text-center text-sm font-bold text-rose-700">{{ (int) $g->alpa }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pt-6">
            {{ $rekap->links() }}
        </div>
    </div>
</div>
@endsection


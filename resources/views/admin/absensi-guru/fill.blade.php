@extends('layouts.admin')

@section('title', 'Input Absensi Guru')

@section('breadcrumb', 'Akademik / Absensi Guru / Input')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="p-6 sm:p-8 border-b border-slate-100 flex flex-col sm:flex-row gap-4 sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Input Absensi Guru</h2>
            <p class="text-sm text-slate-500 mt-1">Isi status kehadiran guru untuk tanggal yang dipilih.</p>
        </div>
        <a href="{{ route('admin.absensi-guru.index') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white text-slate-700 rounded-xl border border-slate-200 hover:bg-slate-50 transition-all font-bold text-sm shadow-sm">
            <span class="material-symbols-outlined text-xl">arrow_back</span>
            Kembali
        </a>
    </div>

    <form method="GET" class="p-6 sm:p-8 border-b border-slate-100 flex flex-col sm:flex-row gap-3 sm:items-end">
        <div class="w-full sm:w-64">
            <label for="tanggal" class="block text-sm font-bold text-slate-700 mb-2">Tanggal</label>
            <input id="tanggal" name="tanggal" value="{{ $tanggal }}" type="date"
                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm"/>
        </div>
        <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary/90 transition-all shadow-lg shadow-primary/20">
            Tampilkan
        </button>
    </form>

    <form action="{{ route('admin.absensi-guru.store-batch') }}" method="POST" id="mainForm" class="p-6 sm:p-8">
        @csrf
        <input type="hidden" name="tanggal" value="{{ $tanggal }}"/>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">No</th>
                        <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Guru</th>
                        <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($guru as $g)
                    @php
                        $ex = $existing[$g->id] ?? null;
                        $status = old('statuses.' . $g->id, $ex->status ?? 'hadir');
                        $ket = old('keterangan.' . $g->id, $ex->keterangan ?? '');
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-4 py-3 text-sm text-slate-500">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-800">{{ $g->nama }}</span>
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $g->nip ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <select name="statuses[{{ $g->id }}]"
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                                <option value="hadir" {{ $status === 'hadir' ? 'selected' : '' }}>Hadir</option>
                                <option value="sakit" {{ $status === 'sakit' ? 'selected' : '' }}>Sakit</option>
                                <option value="izin" {{ $status === 'izin' ? 'selected' : '' }}>Izin</option>
                                <option value="alpa" {{ $status === 'alpa' ? 'selected' : '' }}>Alpa</option>
                            </select>
                        </td>
                        <td class="px-4 py-3">
                            <input type="text" name="keterangan[{{ $g->id }}]" value="{{ $ket }}"
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm"
                                   placeholder="Opsional"/>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pt-6 mt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-end gap-3">
            <a href="{{ route('admin.absensi-guru.index') }}"
               class="w-full sm:w-auto px-8 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-200 transition-all text-center">
                Batal
            </a>
            <button type="submit"
                    class="w-full sm:w-auto px-10 py-3 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-xl">save</span>
                Simpan Absensi
            </button>
        </div>
    </form>
</div>
@endsection


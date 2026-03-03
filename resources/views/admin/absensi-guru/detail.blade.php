@extends('layouts.admin')

@section('title', 'Detail Absensi Guru')

@section('breadcrumb', 'Akademik / Absensi Guru / Detail')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="p-6 sm:p-8 border-b border-slate-100 flex flex-col sm:flex-row gap-4 sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Detail Absensi Guru</h2>
            <p class="text-sm text-slate-500 mt-1">
                Tanggal: <span class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</span>
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.absensi-guru.fill', ['tanggal' => $tanggal]) }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary/90 transition-all shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-xl">edit</span>
                Edit/Input
            </a>
            <a href="{{ route('admin.absensi-guru.index') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white text-slate-700 rounded-xl border border-slate-200 hover:bg-slate-50 transition-all font-bold text-sm shadow-sm">
                <span class="material-symbols-outlined text-xl">arrow_back</span>
                Kembali
            </a>
        </div>
    </div>

    <div class="p-6 sm:p-8">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
            <div class="p-4 rounded-2xl bg-green-50 border border-green-100">
                <p class="text-[10px] font-black text-green-700 uppercase tracking-widest">Hadir</p>
                <p class="text-2xl font-black text-green-800">{{ $stats['hadir'] }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-100">
                <p class="text-[10px] font-black text-amber-700 uppercase tracking-widest">Sakit</p>
                <p class="text-2xl font-black text-amber-800">{{ $stats['sakit'] }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-blue-50 border border-blue-100">
                <p class="text-[10px] font-black text-blue-700 uppercase tracking-widest">Izin</p>
                <p class="text-2xl font-black text-blue-800">{{ $stats['izin'] }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-100">
                <p class="text-[10px] font-black text-rose-700 uppercase tracking-widest">Alpa</p>
                <p class="text-2xl font-black text-rose-800">{{ $stats['alpa'] }}</p>
            </div>
        </div>

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
                        $status = $ex->status ?? null;
                        $ket = $ex->keterangan ?? null;
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
                            @if(!$status)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-600 uppercase tracking-wider">
                                    Belum diisi
                                </span>
                            @else
                                @php
                                    $map = [
                                        'hadir' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Hadir'],
                                        'sakit' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'label' => 'Sakit'],
                                        'izin'  => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Izin'],
                                        'alpa'  => ['bg' => 'bg-rose-100', 'text' => 'text-rose-700', 'label' => 'Alpa'],
                                    ];
                                    $m = $map[$status] ?? ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'label' => strtoupper($status)];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold {{ $m['bg'] }} {{ $m['text'] }} uppercase tracking-wider">
                                    {{ $m['label'] }}
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-600">{{ $ket ?: '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


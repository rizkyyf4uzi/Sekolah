{{-- resources/views/admin/siswa/siswa-aktif/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Detail Siswa')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-950 p-4 sm:p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Breadcrumb -->
        <nav aria-label="Breadcrumb" class="flex mb-6 text-xs font-bold text-slate-400 uppercase tracking-widest">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li><a href="#" class="hover:text-primary transition-colors">Master Data</a></li>
                <li><span class="mx-2 text-slate-300">/</span></li>
                <li><a href="{{ route('admin.siswa.siswa-aktif.index') }}" class="hover:text-primary transition-colors">Data Siswa</a></li>
                <li><span class="mx-2 text-slate-300">/</span></li>
                <li class="text-slate-600 dark:text-slate-300">Detail Siswa</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">
            <div class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-5">
                <div class="relative group">
                    @if($siswa->foto && Storage::disk('public')->exists($siswa->foto))
                        <img src="{{ asset('storage/' . $siswa->foto) }}" 
                             alt="{{ $siswa->nama_lengkap }}" 
                             class="h-24 w-24 sm:h-28 sm:w-28 rounded-3xl object-cover ring-4 ring-white dark:ring-slate-800 shadow-2xl transition-transform group-hover:scale-105 duration-500">
                    @else
                        <div class="h-24 w-24 sm:h-28 sm:w-28 rounded-3xl bg-primary/10 text-primary flex items-center justify-center ring-4 ring-white dark:ring-slate-800 shadow-xl border border-primary/20">
                            <span class="material-symbols-outlined text-4xl sm:text-5xl">person</span>
                        </div>
                    @endif
                    <div class="absolute -bottom-2 -right-2 h-9 w-9 rounded-full bg-white dark:bg-slate-800 shadow-lg flex items-center justify-center border border-slate-100 dark:border-slate-700">
                        <span class="material-symbols-outlined text-primary text-lg font-bold">verified</span>
                    </div>
                </div>
                <div class="space-y-1">
                    <h1 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight leading-tight">
                        {{ $siswa->nama_lengkap }}
                    </h1>
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-2">
                        <span class="px-3 py-1.5 rounded-xl bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[10px] font-black tracking-widest uppercase border border-slate-100 dark:border-slate-700 shadow-sm">
                            NIS: {{ $siswa->nis ?? '-' }}
                        </span>
                        @if($siswa->nisn)
                        <span class="px-3 py-1.5 rounded-xl bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[10px] font-black tracking-widest uppercase border border-slate-100 dark:border-slate-700 shadow-sm">
                            NISN: {{ $siswa->nisn }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('admin.siswa.siswa-aktif.index') }}" 
                   class="flex items-center justify-center gap-2 px-6 py-4 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-2xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all font-bold text-sm shadow-sm group">
                    <span class="material-symbols-outlined text-xl group-hover:-translate-x-1 transition-transform">arrow_back</span>
                    Kembali
                </a>
                <a href="{{ route('admin.siswa.siswa-aktif.edit', $siswa) }}" 
                   class="flex items-center justify-center gap-2 px-6 py-4 bg-primary text-white rounded-2xl hover:bg-primary-dark transition-all font-bold text-sm shadow-xl shadow-primary/20">
                    <span class="material-symbols-outlined text-xl">edit</span>
                    Edit Data
                </a>
            </div>
        </div>

        <!-- Status Banner -->
        <div class="mb-10">
            @php
                $statusConfig = [
                    'aktif' => ['color' => 'bg-emerald-50 text-emerald-700 border-emerald-100', 'icon' => 'check_circle', 'label' => 'Siswa Aktif'],
                    'lulus' => ['color' => 'bg-indigo-50 text-indigo-700 border-indigo-100', 'icon' => 'school', 'label' => 'Sudah Lulus'],
                    'pindah' => ['color' => 'bg-amber-50 text-amber-700 border-amber-100', 'icon' => 'swap_horiz', 'label' => 'Pindah Sekolah'],
                    'cuti' => ['color' => 'bg-slate-100 text-slate-700 border-slate-200', 'icon' => 'pause_circle', 'label' => 'Cuti / Non-Aktif'],
                ];
                $config = $statusConfig[$siswa->status_siswa] ?? $statusConfig['aktif'];
            @endphp
            
            <div class="p-6 sm:p-8 rounded-[2rem] border-2 {{ $config['color'] }} flex flex-col lg:flex-row lg:items-center justify-between gap-8 shadow-sm bg-white/50 backdrop-blur-sm">
                <div class="flex items-center gap-4 sm:gap-5">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-white flex items-center justify-center shadow-sm text-primary flex-shrink-0">
                        <span class="material-symbols-outlined text-3xl sm:text-4xl">{{ $config['icon'] }}</span>
                    </div>
                    <div>
                        <p class="text-[9px] sm:text-[10px] font-black uppercase tracking-[0.2em] opacity-60 mb-0.5 sm:mb-1">Status Akademik</p>
                        <h3 class="font-black text-xl sm:text-2xl tracking-tight leading-tight">{{ $config['label'] }}</h3>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-6 sm:gap-10">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-white flex items-center justify-center text-primary shadow-sm flex-shrink-0">
                            <span class="material-symbols-outlined text-xl sm:text-2xl">calendar_today</span>
                        </div>
                        <div>
                            <p class="text-[9px] sm:text-[10px] font-black uppercase tracking-[0.15em] opacity-60 mb-0.5">Terdaftar Sejak</p>
                            <p class="text-sm sm:text-base font-black">{{ $siswa->tanggal_masuk->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 pt-6 sm:pt-0 border-t sm:border-t-0 sm:border-l border-white/50 sm:pl-10">
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-white flex items-center justify-center text-primary shadow-sm flex-shrink-0">
                            <span class="material-symbols-outlined text-xl sm:text-2xl">group</span>
                        </div>
                        <div>
                            <p class="text-[9px] sm:text-[10px] font-black uppercase tracking-[0.15em] opacity-60 mb-0.5">Kelompok</p>
                            <p class="text-sm sm:text-base font-black">Kelompok {{ $siswa->kelompok }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="mb-8 overflow-x-auto no-scrollbar scroll-smooth">
            <div class="flex p-2 bg-slate-200/50 dark:bg-slate-800/50 backdrop-blur-md rounded-[1.5rem] w-fit min-w-full md:min-w-0">
                <button onclick="showTab('profile')" id="profile-tab" class="tab-btn px-8 py-3.5 rounded-2xl text-sm font-black transition-all flex items-center gap-3 whitespace-nowrap active bg-white dark:bg-slate-700 shadow-sm text-primary">
                    <span class="material-symbols-outlined text-xl">person</span> Data Pribadi
                </button>
                <button onclick="showTab('address')" id="address-tab" class="tab-btn px-8 py-3.5 rounded-2xl text-sm font-black text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 transition-all flex items-center gap-3 whitespace-nowrap">
                    <span class="material-symbols-outlined text-xl">location_on</span> Alamat & Kesehatan
                </button>
                <button onclick="showTab('family')" id="family-tab" class="tab-btn px-8 py-3.5 rounded-2xl text-sm font-black text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 transition-all flex items-center gap-3 whitespace-nowrap">
                    <span class="material-symbols-outlined text-xl">family_history</span> Data Orang Tua
                </button>
                <button onclick="showTab('guardian')" id="guardian-tab" class="tab-btn px-8 py-3.5 rounded-2xl text-sm font-black text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 transition-all flex items-center gap-3 whitespace-nowrap">
                    <span class="material-symbols-outlined text-xl">guardian</span> Data Wali
                </button>
                <button onclick="showTab('academic')" id="academic-tab" class="tab-btn px-8 py-3.5 rounded-2xl text-sm font-black text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 transition-all flex items-center gap-3 whitespace-nowrap">
                    <span class="material-symbols-outlined text-xl">school</span> Akademik
                </button>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="space-y-10">
            <!-- Data Pribadi -->
            <div id="profile" class="tab-content transition-all duration-500 block opacity-100 translate-y-0">
                <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                    <div class="p-6 sm:p-10 border-b border-slate-50 dark:border-slate-700/50 flex items-center gap-4 sm:gap-5">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-primary/10 text-primary flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-2xl sm:text-3xl">person</span>
                        </div>
                        <div>
                            <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">Data Pribadi</h3>
                            <p class="text-[9px] sm:text-sm text-slate-400 font-bold uppercase tracking-widest mt-0.5 sm:mt-1">Identitas Lengkap Siswa</p>
                        </div>
                    </div>
                    <div class="p-6 sm:p-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-8 sm:gap-y-10 gap-x-16">
                        <div class="space-y-2">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Nama Lengkap</p>
                            <p class="text-slate-900 dark:text-white font-black text-lg">{{ $siswa->nama_lengkap }}</p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Nama Panggilan</p>
                            <p class="text-slate-900 dark:text-white font-bold text-lg">{{ $siswa->nama_panggilan ?: '-' }}</p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">NIK</p>
                            <p class="text-slate-900 dark:text-white font-black text-lg font-mono tracking-tighter bg-slate-50 dark:bg-slate-900/50 px-3 py-1 rounded-lg inline-block">{{ $siswa->nik }}</p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Tempat, Tgl Lahir</p>
                            <p class="text-slate-900 dark:text-white font-bold text-lg">
                                {{ $siswa->tempat_lahir }}, {{ $siswa->tanggal_lahir->translatedFormat('d F Y') }}
                            </p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Jenis Kelamin</p>
                            <div class="flex items-center gap-3">
                                @if($siswa->jenis_kelamin == 'L')
                                    <div class="px-4 py-2 rounded-xl bg-blue-50 text-blue-600 font-black text-sm flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-blue-600"></span> Laki-laki
                                    </div>
                                @else
                                    <div class="px-4 py-2 rounded-xl bg-pink-50 text-pink-600 font-black text-sm flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-pink-600"></span> Perempuan
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="space-y-2">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Agama</p>
                            <p class="text-slate-900 dark:text-white font-bold text-lg">{{ $siswa->agama }}</p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Anak Ke</p>
                            <p class="text-slate-900 dark:text-white font-black text-xl">{{ $siswa->anak_ke ?: '-' }}</p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Bahasa Sehari-hari</p>
                            <p class="text-slate-900 dark:text-white font-bold text-lg">{{ $siswa->bahasa_sehari_hari ?: '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alamat & Kesehatan -->
            <div id="address" class="tab-content hidden transition-all duration-500">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <!-- Alamat -->
                    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden flex flex-col">
                        <div class="p-6 sm:p-10 border-b border-slate-50 dark:border-slate-700/50 flex items-center gap-4 sm:gap-5">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-2xl sm:text-3xl">location_on</span>
                            </div>
                            <div>
                                <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">Domisili & Jarak</h3>
                                <p class="text-[9px] sm:text-sm text-slate-400 font-bold uppercase tracking-widest mt-0.5 sm:mt-1">Detail Tempat Tinggal</p>
                            </div>
                        </div>
                        <div class="p-6 sm:p-10 flex-1 flex flex-col justify-between space-y-8 sm:space-y-10">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-8">
                                <div class="bg-slate-50 dark:bg-slate-900/50 p-5 sm:p-6 rounded-3xl space-y-1">
                                    <p class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest">Jarak Rumah</p>
                                    <p class="text-slate-900 dark:text-white font-black text-xl sm:text-2xl tracking-tight">{{ $siswa->jarak_rumah_ke_sekolah ?: '0' }} <span class="text-[10px] font-bold opacity-50">Meter</span></p>
                                </div>
                                <div class="bg-slate-50 dark:bg-slate-900/50 p-5 sm:p-6 rounded-3xl space-y-1">
                                    <p class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest">Waktu Tempuh</p>
                                    <p class="text-slate-900 dark:text-white font-black text-xl sm:text-2xl tracking-tight">{{ $siswa->waktu_tempuh_ke_sekolah ?: '0' }} <span class="text-[10px] font-bold opacity-50">Menit</span></p>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em]">Alamat Lengkap</p>
                                <p class="text-slate-900 dark:text-white font-bold text-lg leading-relaxed">{{ $siswa->alamat }}</p>
                                <div class="flex flex-wrap gap-2 mt-4">
                                    @foreach(['kelurahan', 'kecamatan', 'kota_kabupaten', 'provinsi'] as $geo)
                                        <span class="px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded-lg text-slate-500 dark:text-slate-400 text-[10px] font-black uppercase tracking-widest">{{ $siswa->$geo }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-8 pt-8 border-t border-slate-50 dark:border-slate-700/50">
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tinggal Bersama</p>
                                    <p class="text-slate-900 dark:text-white font-black tracking-tight text-lg">{{ $siswa->tinggal_bersama ?: '-' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Hunian</p>
                                    <p class="text-slate-900 dark:text-white font-black tracking-tight text-lg">{{ $siswa->status_tempat_tinggal ?: '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kesehatan -->
                    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden flex flex-col">
                        <div class="p-6 sm:p-10 border-b border-slate-50 dark:border-slate-700/50 flex items-center gap-4 sm:gap-5">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-2xl sm:text-3xl">medical_services</span>
                            </div>
                            <div>
                                <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">Kondisi Kesehatan</h3>
                                <p class="text-[9px] sm:text-sm text-slate-400 font-bold uppercase tracking-widest mt-0.5 sm:mt-1">Status Fisik & Medis</p>
                            </div>
                        </div>
                        <div class="p-6 sm:p-10 flex-1 flex flex-col justify-between space-y-8 sm:space-y-10">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
                                <div class="bg-rose-50/50 dark:bg-rose-950/20 p-5 sm:p-6 rounded-3xl text-center space-y-1">
                                    <p class="text-[9px] sm:text-[10px] font-black text-rose-400 uppercase tracking-widest">Berat</p>
                                    <p class="text-rose-900 dark:text-rose-200 font-black text-xl sm:text-2xl tracking-tighter">{{ $siswa->berat_badan ?: '-' }}<span class="text-xs ml-0.5 uppercase">kg</span></p>
                                </div>
                                <div class="bg-blue-50/50 dark:bg-blue-950/20 p-5 sm:p-6 rounded-3xl text-center space-y-1">
                                    <p class="text-[9px] sm:text-[10px] font-black text-blue-400 uppercase tracking-widest">Tinggi</p>
                                    <p class="text-blue-900 dark:text-blue-200 font-black text-xl sm:text-2xl tracking-tighter">{{ $siswa->tinggi_badan ?: '-' }}<span class="text-xs ml-0.5 uppercase">cm</span></p>
                                </div>
                                <div class="bg-emerald-50/50 dark:bg-emerald-950/20 p-5 sm:p-6 rounded-3xl text-center space-y-1">
                                    <p class="text-[9px] sm:text-[10px] font-black text-emerald-400 uppercase tracking-widest">GOL Darah</p>
                                    <p class="text-emerald-900 dark:text-emerald-200 font-black text-xl sm:text-2xl tracking-tighter">{{ $siswa->golongan_darah ?: '-' }}</p>
                                </div>
                            </div>
                            <div class="space-y-8">
                                <div class="space-y-2">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em]">Riwayat Penyakit Pernah Diderita</p>
                                    <div class="p-6 bg-slate-50 dark:bg-slate-900/50 rounded-3xl min-h-[80px]">
                                        <p class="text-slate-900 dark:text-white font-bold leading-relaxed">{{ $siswa->penyakit_pernah_diderita ?: 'Tidak ada catatan riwayat penyakit.' }}</p>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em]">Riwayat Imunisasi</p>
                                    <div class="p-6 bg-slate-50 dark:bg-slate-900/50 rounded-3xl min-h-[80px]">
                                        <p class="text-slate-900 dark:text-white font-bold leading-relaxed">{{ $siswa->imunisasi ?: 'Tidak ada catatan riwayat imunisasi.' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Orang Tua -->
            <div id="family" class="tab-content hidden transition-all duration-500">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <!-- Ayah -->
                    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
                        <div class="p-6 sm:p-10 border-b border-slate-50 dark:border-slate-700/50 flex items-center justify-between">
                            <div class="flex items-center gap-4 sm:gap-5">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-2xl sm:text-3xl">man</span>
                                </div>
                                <div>
                                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight leading-tight">Data Ayah</h3>
                                    <p class="text-[9px] sm:text-sm text-slate-400 font-bold uppercase tracking-widest mt-0.5 sm:mt-1">Identitas Orang Tua</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 sm:p-10 grid grid-cols-1 sm:grid-cols-2 gap-y-8 sm:gap-y-10 gap-x-10">
                            <div class="sm:col-span-2 space-y-2">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Nama Lengkap</p>
                                <p class="text-slate-900 dark:text-white font-black text-xl tracking-tight">{{ $siswa->nama_ayah }}</p>
                            </div>
                            <div class="space-y-2 text-sm sm:text-base">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">NIK Ayah</p>
                                <p class="text-slate-900 dark:text-white font-black font-mono tracking-tighter bg-slate-50 dark:bg-slate-900/50 px-3 py-1 rounded-lg inline-block">{{ $siswa->nik_ayah ?: '-' }}</p>
                            </div>
                            <div class="space-y-2 text-sm sm:text-base">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Pendidikan Terakhir</p>
                                <p class="text-slate-900 dark:text-white font-bold">{{ $siswa->pendidikan_ayah ?: '-' }}</p>
                            </div>
                            <div class="space-y-2 text-sm sm:text-base">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Pekerjaan Utama</p>
                                <p class="text-slate-900 dark:text-white font-bold">{{ $siswa->pekerjaan_ayah ?: '-' }}</p>
                            </div>
                            <div class="space-y-2 text-sm sm:text-base">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Penghasilan Bulanan</p>
                                <p class="text-slate-900 dark:text-white font-black text-lg tracking-tight">{{ $siswa->penghasilan_per_bulan_ayah ?: '-' }}</p>
                            </div>
                            <div class="sm:col-span-2 p-5 sm:p-6 bg-emerald-50/50 dark:bg-emerald-950/20 rounded-3xl flex flex-col sm:flex-row items-center gap-4 sm:gap-5 border border-emerald-100 dark:border-emerald-900/30">
                                <div class="w-14 h-14 rounded-2xl bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/20 flex-shrink-0">
                                    <span class="material-symbols-outlined text-2xl font-bold">call</span>
                                </div>
                                <div class="text-center sm:text-left">
                                    <p class="text-[9px] sm:text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-[0.2em] mb-0.5">Nomor Telepon / WhatsApp</p>
                                    <p class="text-emerald-900 dark:text-emerald-200 font-black text-lg sm:text-xl tracking-widest">{{ $siswa->nomor_telepon_ayah ?: '-' }}</p>
                                </div>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siswa->nomor_telepon_ayah) }}" target="_blank" 
                                   class="sm:ml-auto w-full sm:w-12 h-12 rounded-2xl bg-white text-emerald-600 flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all shadow-sm group">
                                    <i class="fab fa-whatsapp text-2xl group-hover:scale-110 transition-transform"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Ibu -->
                    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
                        <div class="p-6 sm:p-10 border-b border-slate-50 dark:border-slate-700/50 flex items-center gap-4 sm:gap-5">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-2xl sm:text-3xl">woman</span>
                            </div>
                            <div>
                                <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight leading-tight">Data Ibu</h3>
                                <p class="text-[9px] sm:text-sm text-slate-400 font-bold uppercase tracking-widest mt-0.5 sm:mt-1">Identitas Orang Tua</p>
                            </div>
                        </div>
                        <div class="p-6 sm:p-10 grid grid-cols-1 sm:grid-cols-2 gap-y-8 sm:gap-y-10 gap-x-10">
                            <div class="sm:col-span-2 space-y-2">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Nama Lengkap</p>
                                <p class="text-slate-900 dark:text-white font-black text-xl tracking-tight">{{ $siswa->nama_ibu }}</p>
                            </div>
                            <div class="space-y-2 text-sm sm:text-base">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">NIK Ibu</p>
                                <p class="text-slate-900 dark:text-white font-black font-mono tracking-tighter bg-slate-50 dark:bg-slate-900/50 px-3 py-1 rounded-lg inline-block">{{ $siswa->nik_ibu ?: '-' }}</p>
                            </div>
                            <div class="space-y-2 text-sm sm:text-base">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Pendidikan Terakhir</p>
                                <p class="text-slate-900 dark:text-white font-bold">{{ $siswa->pendidikan_ibu ?: '-' }}</p>
                            </div>
                            <div class="space-y-2 text-sm sm:text-base">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Pekerjaan Utama</p>
                                <p class="text-slate-900 dark:text-white font-bold">{{ $siswa->pekerjaan_ibu ?: '-' }}</p>
                            </div>
                            <div class="space-y-2 text-sm sm:text-base">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Penghasilan Bulanan</p>
                                <p class="text-slate-900 dark:text-white font-black text-lg tracking-tight">{{ $siswa->penghasilan_per_bulan_ibu ?: '-' }}</p>
                            </div>
                            <div class="sm:col-span-2 p-5 sm:p-6 bg-emerald-50/50 dark:bg-emerald-950/20 rounded-3xl flex flex-col sm:flex-row items-center gap-4 sm:gap-5 border border-emerald-100 dark:border-emerald-900/30">
                                <div class="w-14 h-14 rounded-2xl bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/20 flex-shrink-0">
                                    <span class="material-symbols-outlined text-2xl font-bold">call</span>
                                </div>
                                <div class="text-center sm:text-left">
                                    <p class="text-[9px] sm:text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-[0.2em] mb-0.5">Nomor Telepon / WhatsApp</p>
                                    <p class="text-emerald-900 dark:text-emerald-200 font-black text-lg sm:text-xl tracking-widest">{{ $siswa->nomor_telepon_ibu ?: '-' }}</p>
                                </div>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siswa->nomor_telepon_ibu) }}" target="_blank" 
                                   class="sm:ml-auto w-full sm:w-12 h-12 rounded-2xl bg-white text-emerald-600 flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all shadow-sm group">
                                    <i class="fab fa-whatsapp text-2xl group-hover:scale-110 transition-transform"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Wali -->
            <div id="guardian" class="tab-content hidden transition-all duration-500">
                <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
                    <div class="p-6 sm:p-10 border-b border-slate-50 dark:border-slate-700/50 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex items-center gap-4 sm:gap-5">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-2xl sm:text-3xl">guardian</span>
                            </div>
                            <div>
                                <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight leading-tight">Data Wali Siswa</h3>
                                <p class="text-[9px] sm:text-sm text-slate-400 font-bold uppercase tracking-widest mt-0.5 sm:mt-1">Identitas Penanggung Jawab Alternatif</p>
                            </div>
                        </div>
                        @if(!$siswa->punya_wali || !$siswa->nama_wali)
                            <div class="px-5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900/50 text-slate-400 text-[10px] font-black uppercase tracking-widest border border-slate-100 dark:border-slate-700 w-fit">Tidak Menggunakan Wali</div>
                        @endif
                    </div>
                    
                    @if($siswa->punya_wali && $siswa->nama_wali)
                        <div class="p-6 sm:p-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-8 sm:gap-y-12 gap-x-12">
                            <div class="sm:col-span-2 lg:col-span-1 space-y-2">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Nama Lengkap Wali</p>
                                <p class="text-slate-900 dark:text-white font-black text-xl tracking-tight leading-tight">{{ $siswa->nama_wali }}</p>
                            </div>
                            <div class="space-y-2">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Hubungan Keluarga</p>
                                <div class="inline-flex px-4 py-1.5 rounded-xl bg-purple-50 text-purple-600 font-black text-xs sm:text-sm">{{ $siswa->hubungan_dengan_anak ?: '-' }}</div>
                            </div>
                            <div class="space-y-2">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">NIK Wali</p>
                                <p class="text-slate-900 dark:text-white font-black font-mono tracking-tighter bg-slate-50 dark:bg-slate-900/50 px-3 py-1 rounded-lg inline-block text-sm sm:text-base">{{ $siswa->nik_wali ?: '-' }}</p>
                            </div>
                            <div class="space-y-2">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Pekerjaan</p>
                                <p class="text-slate-900 dark:text-white font-bold text-base sm:text-lg leading-snug">{{ $siswa->pekerjaan_wali ?: '-' }}</p>
                            </div>
                            <div class="sm:col-span-2 lg:col-span-1 space-y-2">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Kontak Wali</p>
                                <div class="flex items-center gap-3">
                                    <p class="text-slate-900 dark:text-white font-black text-lg sm:text-xl tracking-widest">{{ $siswa->nomor_telepon_wali ?: '-' }}</p>
                                    @if($siswa->nomor_telepon_wali)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siswa->nomor_telepon_wali) }}" target="_blank" class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                                            <i class="fab fa-whatsapp text-xl"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="p-20 flex flex-col items-center justify-center text-center space-y-4">
                            <div class="w-20 h-20 rounded-full bg-slate-50 dark:bg-slate-900/50 flex items-center justify-center text-slate-200 dark:text-slate-700">
                                <span class="material-symbols-outlined text-5xl">person_off</span>
                            </div>
                            <p class="text-slate-400 font-bold tracking-tight">Siswa ini tidak terdaftar memiliki wali.<br><span class="text-xs opacity-60 font-medium tracking-normal mt-1 block">Data penanggung jawab utama menggunakan data orang tua.</span></p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Akademik -->
            <div id="academic" class="tab-content hidden transition-all duration-500">
                <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
                    <div class="p-6 sm:p-10 border-b border-slate-50 dark:border-slate-700/50 flex items-center gap-4 sm:gap-5">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-2xl sm:text-3xl">school</span>
                        </div>
                        <div>
                            <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight leading-tight">Informasi Akademik</h3>
                            <p class="text-[9px] sm:text-sm text-slate-400 font-bold uppercase tracking-widest mt-0.5 sm:mt-1">Status & Penempatan Sekolah</p>
                        </div>
                    </div>
                    <div class="p-6 sm:p-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-10 sm:gap-y-12 gap-x-12">
                        <!-- Student Photo in Academic Tab -->
                        <div class="md:col-span-2 lg:col-span-1 border-b md:border-b-0 md:border-r border-slate-100 dark:border-slate-700 pr-0 md:pr-10 pb-10 md:pb-0 flex flex-col items-center lg:items-start text-center lg:text-left">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 w-full">Foto Siswa</p>
                            @if($siswa->foto && Storage::disk('public')->exists($siswa->foto))
                                <img src="{{ asset('storage/' . $siswa->foto) }}" 
                                     alt="{{ $siswa->nama_lengkap }}" 
                                     class="w-full max-w-[180px] sm:max-w-[200px] aspect-[3/4] rounded-3xl object-cover shadow-2xl ring-8 ring-slate-50 dark:ring-slate-900/50 mx-auto lg:mx-0">
                            @else
                                <div class="w-full max-w-[180px] sm:max-w-[200px] aspect-[3/4] rounded-3xl bg-slate-50 dark:bg-slate-900/50 text-slate-300 flex flex-col items-center justify-center border-2 border-dashed border-slate-200 dark:border-slate-800 mx-auto lg:mx-0">
                                    <span class="material-symbols-outlined text-6xl">person</span>
                                    <p class="text-[10px] font-bold mt-2 uppercase tracking-widest">Tidak Ada Foto</p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="md:col-span-2 space-y-10 sm:space-y-12">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 sm:gap-12">
                                <div class="space-y-2">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Tahun Ajaran</p>
                                    <p class="text-slate-900 dark:text-white font-black text-xl sm:text-2xl tracking-tight leading-tight">{{ $siswa->tahunAjaran->tahun_ajaran ?? $siswa->tahun_ajaran }}</p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Kelompok Belajar</p>
                                    <div class="px-5 py-2.5 rounded-2xl bg-indigo-50 text-indigo-600 font-black text-base sm:text-lg inline-block">Kelompok {{ $siswa->kelompok }}</div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 sm:gap-12 pt-10 sm:pt-12 border-t border-slate-50 dark:border-slate-700/50">
                                <div class="space-y-2">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Nama Kelas</p>
                                    <p class="text-slate-900 dark:text-white font-black text-xl sm:text-2xl tracking-tight leading-tight">{{ $siswa->kelas ?: 'Belum ditentukan' }}</p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Guru Kelas</p>
                                    <p class="text-slate-900 dark:text-white font-bold text-lg leading-snug">{{ $siswa->guru_kelas ?: 'Belum ditentukan' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6 sm:p-10 bg-slate-50/50 dark:bg-slate-900/30 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 sm:gap-10">
                        <div class="space-y-2">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Tanggal Masuk</p>
                            <p class="text-slate-900 dark:text-white font-black text-base sm:text-lg">{{ $siswa->tanggal_masuk->translatedFormat('d F Y') }}</p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Jalur Pendaftaran</p>
                            <p class="text-slate-900 dark:text-white font-bold text-base sm:text-lg">{{ $siswa->jalur_masuk ? ucfirst($siswa->jalur_masuk) : 'Reguler' }}</p>
                        </div>
                        <div class="sm:col-span-2 md:col-span-1 space-y-2">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Catatan</p>
                            <p class="text-slate-900 dark:text-white font-medium italic text-sm sm:text-base leading-relaxed">{{ $siswa->catatan ?: '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System & Actions Footer -->
        <div class="mt-12 grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch mb-20">
            <!-- Informasi Sistem -->
            <div class="bg-white dark:bg-slate-800 rounded-[2rem] p-6 sm:p-8 border border-slate-100 dark:border-slate-700 shadow-sm flex flex-col justify-center">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-900 text-slate-500 flex items-center justify-center">
                        <span class="material-symbols-outlined">info</span>
                    </div>
                    <h4 class="font-black text-slate-900 dark:text-white tracking-tight">Informasi Sistem</h4>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-8 text-center sm:text-left">
                    <div class="space-y-1">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">Terakhir Diperbarui</p>
                        <p class="text-sm font-black text-slate-900 dark:text-white">{{ $siswa->updated_at->diffForHumans() }}</p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $siswa->updated_at->translatedFormat('d M Y, H:i') }}</p>
                    </div>
                    <div class="space-y-1 pt-6 sm:pt-0 border-t sm:border-t-0 sm:border-l border-slate-50 dark:border-slate-700/50 sm:pl-8">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">Dibuat Pada</p>
                        <p class="text-sm font-black text-slate-900 dark:text-white">{{ $siswa->created_at->translatedFormat('d M Y') }}</p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $siswa->created_at->format('H:i') }} WIB</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-slate-900 dark:bg-slate-950 rounded-[2rem] p-6 sm:p-8 shadow-2xl flex flex-col justify-center">
                <div class="flex flex-col sm:flex-row gap-6 items-center justify-between text-center sm:text-left">
                    <div>
                        <h4 class="text-white font-black text-lg tracking-tight">Tindakan Cepat</h4>
                        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-1">Kelola status & data siswa</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        <button onclick="showStatusModal()" class="w-full sm:w-auto px-6 py-4 rounded-2xl bg-amber-500 text-white font-black text-xs uppercase tracking-widest shadow-lg shadow-amber-500/20 hover:scale-105 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-lg">sync</span> Update Status
                        </button>
                        <button onclick="confirmDelete('{{ $siswa->id }}', '{{ $siswa->nama_lengkap }}')" class="w-full sm:w-auto px-6 py-4 rounded-2xl bg-rose-600 text-white font-black text-xs uppercase tracking-widest shadow-lg shadow-rose-600/20 hover:scale-105 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-lg">delete</span> Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Status Modal -->
    <div id="statusModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="hideStatusModal()"></div>
        <div class="bg-white dark:bg-slate-800 w-full max-w-lg rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden border border-slate-100 dark:border-slate-700 animate-in fade-in zoom-in duration-300">
            <div class="p-6 sm:p-8 border-b border-slate-50 dark:border-slate-700/50 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">sync</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Update Status</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Ubah status akademik siswa</p>
                    </div>
                </div>
                <button onclick="hideStatusModal()" class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-400 transition-colors flex items-center justify-center">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <form action="{{ route('admin.siswa.siswa-aktif.updateStatus', $siswa) }}" method="POST" class="p-6 sm:p-8 space-y-6">
                @csrf
                @method('PATCH')
                
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih Status Baru</label>
                    <div class="grid grid-cols-1 gap-3">
                        @foreach(['aktif' => 'Aktif', 'non-aktif' => 'Non-Aktif', 'pindah' => 'Pindah', 'keluar' => 'Keluar'] as $value => $label)
                            <label class="relative flex items-center group cursor-pointer">
                                <input type="radio" name="status_siswa" value="{{ $value }}" {{ $siswa->status_siswa == $value ? 'checked' : '' }} class="peer h-0 w-0 opacity-0">
                                <div class="flex-1 px-6 py-4 rounded-2xl border-2 border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 peer-checked:border-primary peer-checked:bg-primary/5 transition-all group-hover:bg-slate-50 dark:group-hover:bg-slate-700/50">
                                    <div class="flex items-center justify-between">
                                        <span class="font-black text-slate-700 dark:text-slate-300 peer-checked:text-primary">{{ $label }}</span>
                                        <div class="w-5 h-5 rounded-full border-2 border-slate-200 dark:border-slate-600 peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center transition-all">
                                            <div class="w-2 h-2 rounded-full bg-white opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
                
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Alasan / Catatan Perubahan</label>
                    <textarea name="catatan" rows="3" placeholder="Tambahkan informasi tambahan jika diperlukan..."
                              class="w-full px-6 py-5 bg-slate-50 dark:bg-slate-900 border-2 border-transparent focus:border-primary focus:ring-0 rounded-2xl text-slate-900 dark:text-white font-bold transition-all placeholder:text-slate-300 resize-none"></textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="hideStatusModal()" class="flex-1 px-6 py-4 rounded-2xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-black text-xs uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-600 transition-all">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-6 py-4 rounded-2xl bg-primary text-white font-black text-xs uppercase tracking-widest shadow-xl shadow-primary/20 hover:scale-[1.02] transition-all active:scale-95">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

<!-- Form Hapus -->
<form id="deleteForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    function showTab(tabName) {
        // Hide all sets
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.add('hidden');
            el.style.opacity = '0';
            el.style.transform = 'translateY(10px)';
        });
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('active', 'bg-white', 'dark:bg-slate-700', 'shadow-sm', 'text-primary');
            el.classList.add('text-slate-500', 'dark:text-slate-400');
        });

        // Show selected with animation
        const activeTab = document.getElementById(tabName);
        activeTab.classList.remove('hidden');
        
        // Trigger reflow
        activeTab.offsetHeight;

        activeTab.style.opacity = '1';
        activeTab.style.transform = 'translateY(0)';

        const activeBtn = document.getElementById(tabName + '-tab');
        activeBtn.classList.add('active', 'bg-white', 'dark:bg-slate-700', 'shadow-sm', 'text-primary');
        activeBtn.classList.remove('text-slate-500', 'dark:text-slate-400');
    }

    function showStatusModal() {
        const modal = document.getElementById('statusModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function hideStatusModal() {
        const modal = document.getElementById('statusModal');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Removed toggleTanggalKeluar as it's not part of the new modal structure.

    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Hapus Data Siswa?',
            html: `Apakah Anda yakin ingin menghapus data <b>${name}</b>?<br><small class="text-rose-500 font-bold uppercase mt-2 block tracking-widest">Tindakan ini tidak dapat dibatalkan!</small>`,
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
                const form = document.getElementById('deleteForm');
                form.action = `/admin/siswa-aktif/${id}`;
                form.submit();
            }
        });
    }

    // Auto-init
    document.addEventListener('DOMContentLoaded', () => {
        showTab('profile');
        // Removed toggleTanggalKeluar() call as the function is removed.
    });
</script>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        @media (max-width: 640px) {
            .tab-btn {
                padding-left: 1rem;
                padding-right: 1rem;
                font-size: 0.75rem;
            }
        }
    
    .active-tab {
        color: var(--primary-color, #4f46e5);
    }
</style>
@endsection
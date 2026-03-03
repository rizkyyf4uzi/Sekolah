<aside class="admin-sidebar w-72 flex-shrink-0 bg-sidebar-bg text-white flex flex-col h-full fixed lg:relative inset-y-0 left-0 -translate-x-full lg:translate-x-0 z-40 lg:z-20 transition-all duration-300">
    <div class="p-6 flex items-center justify-between">
        <div class="flex items-center gap-3 overflow-hidden">
            <div class="bg-white/20 p-2 rounded-xl backdrop-blur-md flex-shrink-0">
                <span class="material-symbols-outlined text-white text-2xl">school</span>
            </div>
            <h2 class="text-xl font-bold tracking-tight logo-text whitespace-nowrap">TK PGRI HARAPAN BANGSA 1</h2>
        </div>
        <label class="cursor-pointer p-1.5 hover:bg-white/10 rounded-lg transition-colors sidebar-toggle-label" for="sidebar-toggle">
            <span class="material-symbols-outlined text-white">menu</span>
        </label>
    </div>

    <div class="sidebar-scroll flex-1 overflow-y-auto px-4 space-y-6 pb-6 mt-2">
        <div>
            <a class="nav-item flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 text-white font-medium shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }} transition-all hover:bg-white/30" href="{{ route('admin.dashboard') }}">
                <span class="material-symbols-outlined text-xl">dashboard</span>
                <span class="text-sm nav-text whitespace-nowrap">Dashboard Overview</span>
            </a>
        </div>

        <div class="space-y-1">
            <div class="nav-section-divider"></div>
            <h3 class="nav-section-title px-4 text-[10px] font-black text-white/60 uppercase tracking-widest mb-3 flex items-center gap-2 whitespace-nowrap">📂 A. Master Data</h3>
            <a class="nav-item flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.siswa.*') ? 'bg-white/20 text-white font-medium shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }} rounded-xl transition-all group" href="{{ route('admin.siswa.siswa-aktif.index') }}" title="Data Siswa">
                <span class="material-symbols-outlined text-lg">group</span>
                <span class="text-sm nav-text whitespace-nowrap">Data Siswa</span>
            </a>
            <a class="nav-item flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.guru.*') ? 'bg-white/20 text-white font-medium shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }} rounded-xl transition-all group" href="{{ route('admin.guru.index') }}" title="Data Guru">
                <span class="material-symbols-outlined text-lg">person_pin_circle</span>
                <span class="text-sm nav-text whitespace-nowrap">Data Guru</span>
            </a>
            <a class="nav-item flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.tahun-ajaran.*') ? 'bg-white/20 text-white font-medium shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }} rounded-xl transition-all group" href="{{ route('admin.tahun-ajaran.index') }}" title="Tahun Ajaran">
                <span class="material-symbols-outlined text-lg">calendar_month</span>
                <span class="text-sm nav-text whitespace-nowrap">Tahun Ajaran</span>
            </a>
        </div>

        <div class="space-y-1">
            <div class="nav-section-divider"></div>
            <h3 class="nav-section-title px-4 text-[10px] font-black text-white/60 uppercase tracking-widest mb-3 flex items-center gap-2 whitespace-nowrap">📚 B. Akademik</h3>
            <a class="nav-item flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.absensi.index') ? 'bg-white/20 text-white font-medium shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }} rounded-xl transition-all" href="{{ route('admin.absensi.index') }}" title="Absensi Siswa">
                <span class="material-symbols-outlined text-lg">how_to_reg</span>
                <span class="text-sm nav-text whitespace-nowrap">Absensi Siswa</span>
            </a>
            <a class="nav-item flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.absensi-guru.*') ? 'bg-white/20 text-white font-medium shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }} rounded-xl transition-all" href="{{ route('admin.absensi-guru.index') }}" title="Absensi Guru">
                <span class="material-symbols-outlined text-lg">badge</span>
                <span class="text-sm nav-text whitespace-nowrap">Absensi Guru</span>
            </a>
            <a class="nav-item flex items-center gap-3 px-4 py-2.5 text-white/80 hover:bg-white/10 hover:text-white rounded-xl transition-all" href="#" onclick="return false;" title="Materi KBM">
                <span class="material-symbols-outlined text-lg">auto_stories</span>
                <span class="text-sm nav-text whitespace-nowrap">Materi KBM</span>
            </a>
            <a class="nav-item flex items-center gap-3 px-4 py-2.5 text-white/80 hover:bg-white/10 hover:text-white rounded-xl transition-all" href="#" onclick="return false;" title="Kalender Akademik">
                <span class="material-symbols-outlined text-lg">event_note</span>
                <span class="text-sm nav-text whitespace-nowrap">Kalender Akademik</span>
            </a>
        </div>

        <div class="space-y-1">
            <div class="nav-section-divider"></div>
            <h3 class="nav-section-title px-4 text-[10px] font-black text-white/60 uppercase tracking-widest mb-3 flex items-center gap-2 whitespace-nowrap">🏫 C. PPDB</h3>
            <a class="nav-item flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.spmb.index') && !request()->has('status') ? 'bg-white/20 text-white font-medium shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }} rounded-xl transition-all" href="{{ route('admin.spmb.index') }}" title="Pendaftaran">
                <span class="material-symbols-outlined text-lg">app_registration</span>
                <span class="text-sm nav-text whitespace-nowrap">Pendaftaran</span>
            </a>
            <a class="nav-item flex items-center gap-3 px-4 py-2.5 {{ request()->get('status') == 'Menunggu Verifikasi' ? 'bg-white/20 text-white font-medium shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }} rounded-xl transition-all" href="{{ route('admin.spmb.index', ['status' => 'Menunggu Verifikasi']) }}" title="Verifikasi Dokumen">
                <span class="material-symbols-outlined text-lg">verified</span>
                <span class="text-sm nav-text whitespace-nowrap">Verifikasi Dokumen</span>
            </a>
            <a class="nav-item flex items-center gap-3 px-4 py-2.5 {{ request()->get('status') == 'Diterima' ? 'bg-white/20 text-white font-medium shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }} rounded-xl transition-all" href="{{ route('admin.spmb.index', ['status' => 'Diterima']) }}" title="Pengumuman">
                <span class="material-symbols-outlined text-lg">campaign</span>
                <span class="text-sm nav-text whitespace-nowrap">Pengumuman</span>
            </a>
            <a class="nav-item flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.spmb-settings.*') ? 'bg-white/20 text-white font-medium shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }} rounded-xl transition-all" href="{{ route('admin.spmb-settings.index') }}" title="Settings PPDB">
                <span class="material-symbols-outlined text-lg">settings</span>
                <span class="text-sm nav-text whitespace-nowrap">Settings PPDB</span>
            </a>
            <a class="nav-item flex items-center gap-3 px-4 py-2.5 text-white/80 hover:bg-white/10 hover:text-white rounded-xl transition-all" href="#" onclick="return false;" title="Riwayat PPDB">
                <span class="material-symbols-outlined text-lg">history</span>
                <span class="text-sm nav-text whitespace-nowrap">Riwayat PPDB</span>
            </a>
            <a class="nav-item flex items-center gap-3 px-4 py-2.5 text-white/80 hover:bg-white/10 hover:text-white rounded-xl transition-all" href="{{ route('admin.spmb.export') }}" title="Export Data">
                <span class="material-symbols-outlined text-lg">download</span>
                <span class="text-sm nav-text whitespace-nowrap">Export Data</span>
            </a>
        </div>

        <div class="space-y-1">
            <div class="nav-section-divider"></div>
            <h3 class="nav-section-title px-4 text-[10px] font-black text-white/60 uppercase tracking-widest mb-3 flex items-center gap-2 whitespace-nowrap">🌐 D. Informasi Publik</h3>
            <a class="nav-item flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.galeri.*') ? 'bg-white/20 text-white font-medium shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }} rounded-xl transition-all" href="{{ route('admin.galeri.index') }}" title="Galeri">
                <span class="material-symbols-outlined text-lg">photo_library</span>
                <span class="text-sm nav-text whitespace-nowrap">Galeri</span>
            </a>
            <a class="nav-item flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.berita.*') ? 'bg-white/20 text-white font-medium shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }} rounded-xl transition-all" href="{{ route('admin.berita.index') }}" title="Kegiatan Sekolah">
                <span class="material-symbols-outlined text-lg">festival</span>
                <span class="text-sm nav-text whitespace-nowrap">Kegiatan Sekolah</span>
            </a>
            <a class="nav-item flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.bukutamu.*') ? 'bg-white/20 text-white font-medium shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }} rounded-xl transition-all" href="{{ route('admin.bukutamu.index') }}" title="Buku Tamu">
                <span class="material-symbols-outlined text-lg">book</span>
                <span class="text-sm nav-text whitespace-nowrap">Buku Tamu</span>
            </a>
        </div>

        @if(auth()->user() && in_array(auth()->user()->role, ['admin', 'super_admin']))
        <div class="space-y-1">
            <div class="nav-section-divider"></div>
            <h3 class="nav-section-title px-4 text-[10px] font-black text-white/60 uppercase tracking-widest mb-3 flex items-center gap-2 whitespace-nowrap">⚙ E. Manajemen Sistem (Admin Only)</h3>
            <a class="nav-item flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.accounts.*') ? 'bg-white/20 text-white font-medium shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }} rounded-xl transition-all" href="{{ route('admin.accounts.index') }}" title="Kelola User">
                <span class="material-symbols-outlined text-lg">manage_accounts</span>
                <span class="text-sm nav-text whitespace-nowrap">Kelola User</span>
            </a>
            <a class="nav-item flex items-center gap-3 px-4 py-2.5 text-white/80 hover:bg-white/10 hover:text-white rounded-xl transition-all" href="#" onclick="return false;" title="Role & Permission">
                <span class="material-symbols-outlined text-lg">verified_user</span>
                <span class="text-sm nav-text whitespace-nowrap">Role & Permission</span>
            </a>
            <a class="nav-item flex items-center gap-3 px-4 py-2.5 text-white/80 hover:bg-white/10 hover:text-white rounded-xl transition-all" href="#" onclick="return false;" title="Log Aktivitas">
                <span class="material-symbols-outlined text-lg">list_alt</span>
                <span class="text-sm nav-text whitespace-nowrap">Log Aktivitas</span>
            </a>
        </div>
        @endif
    </div>

    <form method="POST" action="{{ route('logout') }}" id="logoutForm" class="p-4 border-t border-white/10">
        @csrf
        <button type="button" onclick="confirmLogout()" class="nav-item w-full flex items-center justify-center gap-2 px-4 py-2 text-white/80 hover:bg-white/10 rounded-xl transition-all text-sm">
            <span class="material-symbols-outlined text-lg">logout</span>
            <span class="nav-text">Keluar</span>
        </button>
    </form>
</aside>

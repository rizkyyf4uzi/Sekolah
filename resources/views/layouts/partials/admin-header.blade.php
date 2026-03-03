<header class="h-20 flex items-center justify-between px-8 bg-transparent flex-shrink-0">
    <button type="button" id="mobileMenuButton" class="lg:hidden p-2.5 rounded-2xl hover:bg-white transition-all" aria-label="Buka menu">
        <span class="material-symbols-outlined text-slate-600">menu</span>
    </button>

    <div class="relative flex-1 max-w-md mx-auto lg:mx-0 lg:mr-auto lg:ml-0">
        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
        <input class="w-full pl-12 pr-4 py-2.5 bg-white border-none rounded-2xl focus:ring-2 focus:ring-primary/20 text-sm shadow-sm transition-all" placeholder="Search for students, teachers, documents..." type="text"/>
    </div>

    <div class="flex items-center gap-6">
        <div class="flex items-center gap-2">
            <button type="button" class="p-2.5 text-slate-600 hover:bg-white rounded-2xl transition-all shadow-sm relative">
                <span class="material-symbols-outlined">notifications</span>
                <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>
            <button type="button" class="p-2.5 text-slate-600 hover:bg-white rounded-2xl transition-all shadow-sm">
                <span class="material-symbols-outlined">mail</span>
            </button>
        </div>
        
        {{-- Profile Dropdown with Vanilla JS --}}
        <div class="relative" id="profileDropdown">
            {{-- Profile Button --}}
            <button id="profileButton" class="flex items-center gap-3 focus:outline-none">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-slate-800">@auth {{ Auth::user()->name }} @else Guest @endauth</p>
                    <p class="text-[10px] font-medium text-slate-400 uppercase tracking-widest">
                        @auth
                            @switch(Auth::user()->role)
                                @case('admin')
                                @case('super_admin')
                                    Super Admin
                                    @break
                                @case('kepala_sekolah')
                                    Kepala Sekolah
                                    @break
                                @case('operator')
                                    Operator
                                    @break
                                @case('guru')
                                    Guru
                                    @break
                                @default
                                    {{ ucfirst(Auth::user()->role) }}
                            @endswitch
                        @endauth
                    </p>
                </div>
                
                {{-- Profile Photo/Initial --}}
                <div class="relative">
                    @auth
                        @php
                            $fotoPath = Auth::user()->foto ?? null;
                            $hasFoto = $fotoPath && Storage::disk('public')->exists($fotoPath);
                        @endphp
                        
                        @if($hasFoto)
                        <img alt="{{ Auth::user()->name }}" 
                             class="w-10 h-10 rounded-2xl object-cover shadow-sm ring-2 ring-white" 
                             src="{{ Storage::url($fotoPath) }}?v={{ time() }}"
                             onerror="this.style.display='none'; this.parentNode.querySelector('.avatar-fallback').style.display='flex';"/>
                        <span class="avatar-fallback w-10 h-10 rounded-2xl bg-primary text-white flex items-center justify-center text-lg font-bold shadow-sm ring-2 ring-white" style="display: none;">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        @else
                        <span class="w-10 h-10 rounded-2xl bg-primary text-white flex items-center justify-center text-lg font-bold shadow-sm ring-2 ring-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        @endif
                    @else
                    <span class="w-10 h-10 rounded-2xl bg-slate-400 text-white flex items-center justify-center text-lg font-bold shadow-sm ring-2 ring-white">?</span>
                    @endauth
                </div>
            </button>

            {{-- Dropdown Menu --}}
            <div id="dropdownMenu" class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 hidden">
                
                {{-- User Info --}}
                <div class="px-4 py-3 border-b border-slate-100">
                    <p class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">{{ Auth::user()->email }}</p>
                </div>

                {{-- Menu Items --}}
                <div class="py-1">
                    {{-- Profile --}}
                    <a href="{{ route('admin.profile.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-lavender/50 transition-all">
                        <span class="material-symbols-outlined text-primary text-lg">person</span>
                        <span class="font-medium">Profil Saya</span>
                    </a>

                    {{-- Settings --}}
                    <a href="{{ route('admin.profile.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-lavender/50 transition-all">
                        <span class="material-symbols-outlined text-primary text-lg">settings</span>
                        <span class="font-medium">Pengaturan</span>
                    </a>

                    {{-- Divider --}}
                    <div class="border-t border-slate-100 my-1"></div>

                    {{-- Keluar dengan Konfirmasi --}}
                    <button type="button" 
                            id="logoutButton"
                            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-all">
                        <span class="material-symbols-outlined text-red-500 text-lg">logout</span>
                        <span class="font-medium">Keluar</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

{{-- Hidden Logout Form --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>

{{-- Vanilla JavaScript for Dropdown --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profileButton = document.getElementById('profileButton');
        const dropdownMenu = document.getElementById('dropdownMenu');
        const logoutButton = document.getElementById('logoutButton');
        const logoutForm = document.getElementById('logout-form');
        
        // Toggle dropdown on button click
        if (profileButton && dropdownMenu) {
            profileButton.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('hidden');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!profileButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.classList.add('hidden');
                }
            });
            
            // Close dropdown on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    dropdownMenu.classList.add('hidden');
                }
            });
        }
        
        // Logout confirmation
        if (logoutButton && logoutForm) {
            logoutButton.addEventListener('click', function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Konfirmasi Logout',
                    text: 'Apakah Anda yakin ingin keluar dari sistem?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Keluar',
                    cancelButtonText: 'Batal',
                    background: '#ffffff',
                    backdrop: `rgba(0,0,0,0.6)`,
                    customClass: {
                        title: 'text-lg font-bold text-slate-800',
                        htmlContainer: 'text-sm text-slate-600',
                        confirmButton: 'px-4 py-2 rounded-lg text-sm font-medium',
                        cancelButton: 'px-4 py-2 rounded-lg text-sm font-medium'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tampilkan loading
                        Swal.fire({
                            title: 'Logout...',
                            text: 'Sedang memproses',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Submit form logout
                        logoutForm.submit();
                    }
                });
            });
        }
    });
</script>
@endpush
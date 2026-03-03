@extends('layouts.admin')

@section('breadcrumb', 'Profile')

@push('styles')
<style>
    .profile-photo-container {
        position: relative;
        width: 150px;
        height: 150px;
        margin: 0 auto;
    }
    
    .profile-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    .profile-photo-overlay {
        position: absolute;
        bottom: 0;
        right: 0;
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 3px solid white;
        transition: all 0.3s ease;
    }
    
    .profile-photo-overlay:hover {
        transform: scale(1.1);
        background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
    }
    
    .role-badge {
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .role-super-admin { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; }
    .role-admin { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; }
    .role-operator { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
    .role-guru { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; }
    
    .info-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
    }
    
    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .info-label {
        font-size: 0.75rem;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.25rem;
    }
    
    .info-value {
        font-size: 1rem;
        font-weight: 600;
        color: #111827;
    }
    
    .tab-button {
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .tab-button.active {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.3);
    }
    
    .tab-pane {
        display: none;
    }
    
    .tab-pane.active {
        display: block;
        animation: fadeIn 0.5s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .password-strength {
        height: 5px;
        border-radius: 5px;
        transition: all 0.3s ease;
        margin-top: 5px;
    }
    
    .strength-weak { background: #ef4444; width: 33.33%; }
    .strength-medium { background: #f59e0b; width: 66.66%; }
    .strength-strong { background: #10b981; width: 100%; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Profile Saya</h2>
        <p class="text-sm text-gray-600">Kelola informasi profile dan pengaturan akun Anda</p>
    </div>

    <!-- Profile Overview Card -->
    <div class="info-card mb-6">
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
            <!-- Profile Photo -->
            <div class="profile-photo-container">
                <img src="{{ $user->foto ? Storage::url($user->foto) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=4f46e5&color=fff&size=150' }}" 
                     alt="Profile Photo" 
                     class="profile-photo"
                     id="profilePhoto">
                <div class="profile-photo-overlay" onclick="document.getElementById('photoInput').click()">
                    <i class="fas fa-camera text-white text-sm"></i>
                </div>
                <input type="file" id="photoInput" accept="image/*" class="hidden">
            </div>
            
            <!-- Profile Info -->
            <div class="flex-1 text-center md:text-left">
                <h3 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h3>
                <div class="flex items-center justify-center md:justify-start gap-2 mt-2">
                    <span class="role-badge role-{{ str_replace(' ', '-', strtolower($user->role)) }}">
                        {{ ucfirst($user->role) }}
                    </span>
                    <span class="text-sm text-gray-500">
                        <i class="fas fa-envelope mr-1"></i>{{ $user->email }}
                    </span>
                </div>
                
                @if($user->role === 'guru' && $user->guru)
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <div class="info-label">NIP</div>
                        <div class="info-value">{{ $user->guru->nip ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="info-label">NUPTK</div>
                        <div class="info-value">{{ $user->guru->nuptk ?? '-' }}</div>
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Join Date -->
            <div class="text-right hidden md:block">
                <div class="info-label">Member Sejak</div>
                <div class="info-value">{{ $user->created_at->translatedFormat('d F Y') }}</div>
                <div class="text-xs text-gray-500 mt-1">
                    <i class="fas fa-clock mr-1"></i>{{ $user->created_at->diffForHumans() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs - Dengan Ikon (Hemat Ruang) -->
    <div class="grid grid-cols-3 gap-2 mb-6">
        <button class="tab-button active flex flex-col items-center py-3" onclick="switchTab('profile')">
            <i class="fas fa-user text-xl mb-1"></i>
            <span class="text-xs">Profile</span>
        </button>
        <button class="tab-button flex flex-col items-center py-3" onclick="switchTab('password')">
            <i class="fas fa-lock text-xl mb-1"></i>
            <span class="text-xs">Password</span>
        </button>
        <button class="tab-button flex flex-col items-center py-3" onclick="switchTab('activity')">
            <i class="fas fa-history text-xl mb-1"></i>
            <span class="text-xs">Aktivitas</span>
        </button>
    </div>

    <!-- Tab Panes -->
    <div class="info-card">
        <!-- Profile Information Tab -->
        <div id="tab-profile" class="tab-pane active">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Informasi Profile</h4>
            
            <form id="profileForm" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user text-indigo-500 mr-2"></i>Nama Lengkap
                        </label>
                        <input type="text" 
                               name="name" 
                               value="{{ $user->name }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                               required>
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope text-indigo-500 mr-2"></i>Email
                        </label>
                        <input type="email" 
                               name="email" 
                               value="{{ $user->email }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                               required>
                    </div>
                    
                    <!-- Jenis Kelamin -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-venus-mars text-indigo-500 mr-2"></i>Jenis Kelamin
                        </label>
                        <select name="jenis_kelamin" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" {{ ($user->jenis_kelamin ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ ($user->jenis_kelamin ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    
                    <!-- Tanggal Lahir -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar text-indigo-500 mr-2"></i>Tanggal Lahir
                        </label>
                        <input type="date" 
                               name="tanggal_lahir" 
                               value="{{ $user->tanggal_lahir ?? '' }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                    </div>
                    
                    <!-- No Telepon -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-phone text-indigo-500 mr-2"></i>No. Telepon
                        </label>
                        <input type="text" 
                               name="no_telepon" 
                               value="{{ $user->no_telepon ?? '' }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                               placeholder="08xxxxxxxxxx">
                    </div>
                    
                    <!-- Alamat -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt text-indigo-500 mr-2"></i>Alamat
                        </label>
                        <textarea name="alamat" 
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                                  placeholder="Alamat lengkap">{{ $user->alamat ?? '' }}</textarea>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button" 
                            onclick="resetProfileForm()"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-all duration-200">
                        <i class="fas fa-undo mr-2"></i>Reset
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Password Change Tab -->
        <div id="tab-password" class="tab-pane">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Ubah Password</h4>
            
            <form id="passwordForm" class="space-y-6 max-w-md">
                @csrf
                @method('PUT')
                
                <!-- Current Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock text-indigo-500 mr-2"></i>Password Saat Ini
                    </label>
                    <div class="relative">
                        <input type="password" 
                               name="current_password" 
                               id="current_password"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 pr-10"
                               required>
                        <button type="button" 
                                onclick="togglePassword('current_password')"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-indigo-600">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <!-- New Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-key text-indigo-500 mr-2"></i>Password Baru
                    </label>
                    <div class="relative">
                        <input type="password" 
                               name="new_password" 
                               id="new_password"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 pr-10"
                               required
                               onkeyup="checkPasswordStrength(this.value)">
                        <button type="button" 
                                onclick="togglePassword('new_password')"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-indigo-600">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <!-- Password Strength Indicator -->
                    <div class="password-strength" id="passwordStrength"></div>
                    <div class="text-xs text-gray-500 mt-2">
                        <span id="strengthText">Masukkan password</span>
                    </div>
                    
                    <!-- Password Requirements -->
                    <div class="mt-3 text-xs space-y-1">
                        <div class="requirement" id="reqLength">
                            <i class="fas fa-circle text-gray-300 mr-1"></i> Minimal 8 karakter
                        </div>
                        <div class="requirement" id="reqUppercase">
                            <i class="fas fa-circle text-gray-300 mr-1"></i> Huruf besar (A-Z)
                        </div>
                        <div class="requirement" id="reqLowercase">
                            <i class="fas fa-circle text-gray-300 mr-1"></i> Huruf kecil (a-z)
                        </div>
                        <div class="requirement" id="reqNumber">
                            <i class="fas fa-circle text-gray-300 mr-1"></i> Angka (0-9)
                        </div>
                        <div class="requirement" id="reqSpecial">
                            <i class="fas fa-circle text-gray-300 mr-1"></i> Karakter spesial (!@#$%^&*)
                        </div>
                    </div>
                </div>
                
                <!-- Confirm New Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-check-circle text-indigo-500 mr-2"></i>Konfirmasi Password Baru
                    </label>
                    <div class="relative">
                        <input type="password" 
                               name="new_password_confirmation" 
                               id="new_password_confirmation"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 pr-10"
                               required
                               onkeyup="checkPasswordMatch()">
                        <button type="button" 
                                onclick="togglePassword('new_password_confirmation')"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-indigo-600">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="text-xs mt-1" id="passwordMatchMessage"></div>
                </div>
                
                <!-- Form Actions -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button" 
                            onclick="resetPasswordForm()"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-all duration-200">
                        <i class="fas fa-undo mr-2"></i>Reset
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-key mr-2"></i>Ubah Password
                    </button>
                </div>
            </form>
        </div>

        <!-- Activity Log Tab -->
        <div id="tab-activity" class="tab-pane">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-semibold text-gray-900">Aktivitas Terakhir</h4>
                @if(isset($activities) && $activities->count() > 0)
                <span class="text-xs text-gray-500">Total {{ $activities->count() }} aktivitas</span>
                @endif
            </div>
            
            @if(isset($activities) && $activities->count() > 0)
            <div class="space-y-3">
                @foreach($activities as $activity)
                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all duration-200">
                    <!-- Icon berdasarkan action -->
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0
                        @if($activity->action == 'login') bg-green-100
                        @elseif($activity->action == 'logout') bg-gray-100
                        @elseif($activity->action == 'update_profile') bg-blue-100
                        @elseif($activity->action == 'change_password') bg-yellow-100
                        @elseif($activity->action == 'update_photo') bg-purple-100
                        @else bg-indigo-100
                        @endif">
                        
                        @if($activity->action == 'login')
                            <i class="fas fa-sign-in-alt text-green-600 text-sm"></i>
                        @elseif($activity->action == 'logout')
                            <i class="fas fa-sign-out-alt text-gray-600 text-sm"></i>
                        @elseif($activity->action == 'update_profile')
                            <i class="fas fa-user-edit text-blue-600 text-sm"></i>
                        @elseif($activity->action == 'change_password')
                            <i class="fas fa-key text-yellow-600 text-sm"></i>
                        @elseif($activity->action == 'update_photo')
                            <i class="fas fa-camera text-purple-600 text-sm"></i>
                        @else
                            <i class="fas fa-history text-indigo-600 text-sm"></i>
                        @endif
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $activity->description ?? ucfirst(str_replace('_', ' ', $activity->action)) }}
                            </p>
                            <span class="text-xs text-gray-500">
                                {{ $activity->created_at->diffForHumans() }}
                            </span>
                        </div>
                        
                        <!-- Device Info -->
                        <div class="flex items-center gap-3 mt-1 text-xs text-gray-500">
                            <span>
                                <i class="fas fa-globe mr-1"></i>{{ $activity->ip_address ?? '127.0.0.1' }}
                            </span>
                            <span>
                                <i class="fas fa-{{ strtolower($activity->device) == 'mobile' ? 'mobile-alt' : 'desktop' }} mr-1"></i>
                                {{ $activity->device ?? 'Unknown' }}
                            </span>
                            <span>
                                <i class="fas fa-compass mr-1"></i>{{ $activity->browser ?? 'Unknown' }}
                            </span>
                        </div>

                        <!-- Tampilkan perubahan data dengan format yang lebih baik -->
                        @if($activity->action == 'update_profile' && ($activity->old_data || $activity->new_data))
                        <div class="mt-2 p-2 bg-white rounded border border-gray-200">
                            <p class="text-xs font-medium text-gray-700 mb-1">Perubahan:</p>
                            <div class="space-y-1">
                                @php
                                    $old = is_string($activity->old_data) ? json_decode($activity->old_data, true) : $activity->old_data;
                                    $new = is_string($activity->new_data) ? json_decode($activity->new_data, true) : $activity->new_data;
                                @endphp
                                
                                @if($old && $new)
                                    @foreach($new as $key => $value)
                                        @if(isset($old[$key]) && $old[$key] != $value)
                                            <div class="text-xs">
                                                <span class="text-gray-500">{{ ucfirst($key) }}:</span>
                                                <span class="text-red-600 line-through mr-2">{{ $old[$key] }}</span>
                                                <i class="fas fa-arrow-right text-xs text-gray-400 mx-1"></i>
                                                <span class="text-green-600">{{ $value }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            
            @else
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-history text-gray-400 text-2xl"></i>
                </div>
                <p class="text-gray-600">Belum ada aktivitas</p>
                <p class="text-sm text-gray-500 mt-1">Aktivitas Anda akan muncul di sini</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Cropper Modal for Photo - DIPERKECIL -->
<div id="cropperModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-4 max-w-sm w-full mx-4">
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-base font-semibold text-gray-900">Sesuaikan Foto</h3>
            <button type="button" onclick="closeCropper()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Container cropper dengan ukuran tetap -->
        <div class="mb-3">
            <div class="relative w-full" style="height: 250px;">
                <img id="cropperImage" src="" alt="Image to crop" class="max-w-full max-h-full">
            </div>
        </div>
        
        <!-- Info ukuran -->
        <p class="text-xs text-gray-500 mb-3">Foto akan disimpan ukuran 300x300 px</p>
        
        <!-- Tombol aksi -->
        <div class="flex justify-end gap-2">
            <button type="button" onclick="closeCropper()" 
                    class="px-3 py-1.5 text-sm border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                Batal
            </button>
            <button type="button" onclick="cropAndUpload()" 
                    class="px-3 py-1.5 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                <i class="fas fa-crop mr-1"></i>Simpan
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Cropper.js -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
let cropper;
let selectedFile;

// Tab switching
function switchTab(tab) {
    // Update tab buttons
    document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
    event.target.closest('.tab-button').classList.add('active');
    
    // Update tab panes
    document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));
    document.getElementById(`tab-${tab}`).classList.add('active');
}

// Photo upload handling
document.getElementById('photoInput').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        selectedFile = e.target.files[0];
        
        // Validate file type
        if (!selectedFile.type.match('image.*')) {
            Swal.fire('Error', 'File harus berupa gambar', 'error');
            return;
        }
        
        // Validate file size (max 2MB)
        if (selectedFile.size > 2 * 1024 * 1024) {
            Swal.fire('Error', 'Ukuran file maksimal 2MB', 'error');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('cropperImage').src = e.target.result;
            document.getElementById('cropperModal').classList.remove('hidden');
            document.getElementById('cropperModal').classList.add('flex');
            
            if (cropper) {
                cropper.destroy();
            }
            
            cropper = new Cropper(document.getElementById('cropperImage'), {
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 1,
                cropBoxMovable: false,
                cropBoxResizable: false,
                background: false,
                center: true,
            });
        };
        reader.readAsDataURL(selectedFile);
    }
});

function closeCropper() {
    document.getElementById('cropperModal').classList.add('hidden');
    document.getElementById('cropperModal').classList.remove('flex');
    if (cropper) {
        cropper.destroy();
    }
    document.getElementById('photoInput').value = '';
}

function cropAndUpload() {
    if (!cropper) return;
    
    const canvas = cropper.getCroppedCanvas({
        width: 300,
        height: 300,
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high',
    });
    
    canvas.toBlob(function(blob) {
        const formData = new FormData();
        formData.append('photo', blob, 'profile.jpg');
        formData.append('_token', '{{ csrf_token() }}');
        
        window.showLoading();
        
        fetch('{{ route("admin.profile.update-photo") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            window.hideLoading();
            if (data.success) {
                document.getElementById('profilePhoto').src = data.photo_url + '?t=' + new Date().getTime();
                Swal.fire('Sukses', data.message, 'success');
                closeCropper();
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(error => {
            window.hideLoading();
            Swal.fire('Error', 'Terjadi kesalahan saat mengupload foto', 'error');
        });
    }, 'image/jpeg', 0.9);
}

// Profile form submission
document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    window.showLoading();
    
    fetch('{{ route("admin.profile.update") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        window.hideLoading();
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });
            
            // Update user name di sidebar dan header
            location.reload();
        } else {
            if (data.errors) {
                let errorMessage = '';
                for (let key in data.errors) {
                    errorMessage += data.errors[key][0] + '\n';
                }
                Swal.fire('Error', errorMessage, 'error');
            } else {
                Swal.fire('Error', data.message || 'Terjadi kesalahan', 'error');
            }
        }
    })
    .catch(error => {
        window.hideLoading();
        Swal.fire('Error', 'Terjadi kesalahan jaringan', 'error');
    });
});

// Password form submission
document.getElementById('passwordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validate password match
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('new_password_confirmation').value;
    
    if (newPassword !== confirmPassword) {
        Swal.fire('Error', 'Password baru dan konfirmasi tidak cocok', 'error');
        return;
    }
    
    // Validate password strength
    if (!isPasswordStrong(newPassword)) {
        Swal.fire('Error', 'Password tidak memenuhi kriteria keamanan', 'error');
        return;
    }
    
    const formData = new FormData(this);
    
    window.showLoading();
    
    fetch('{{ route("admin.profile.change-password") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        window.hideLoading();
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });
            resetPasswordForm();
        } else {
            if (data.errors) {
                let errorMessage = '';
                for (let key in data.errors) {
                    errorMessage += data.errors[key][0] + '\n';
                }
                Swal.fire('Error', errorMessage, 'error');
            } else {
                Swal.fire('Error', data.message || 'Terjadi kesalahan', 'error');
            }
        }
    })
    .catch(error => {
        window.hideLoading();
        Swal.fire('Error', 'Terjadi kesalahan jaringan', 'error');
    });
});

// Toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const type = field.type === 'password' ? 'text' : 'password';
    field.type = type;
    
    // Toggle eye icon
    const button = field.nextElementSibling;
    const icon = button.querySelector('i');
    icon.classList.toggle('fa-eye');
    icon.classList.toggle('fa-eye-slash');
}

// Check password strength
function checkPasswordStrength(password) {
    const strengthBar = document.getElementById('passwordStrength');
    const strengthText = document.getElementById('strengthText');
    
    // Requirements
    const hasLength = password.length >= 8;
    const hasUppercase = /[A-Z]/.test(password);
    const hasLowercase = /[a-z]/.test(password);
    const hasNumber = /[0-9]/.test(password);
    const hasSpecial = /[!@#$%^&*]/.test(password);
    
    // Update requirement indicators
    updateRequirement('reqLength', hasLength);
    updateRequirement('reqUppercase', hasUppercase);
    updateRequirement('reqLowercase', hasLowercase);
    updateRequirement('reqNumber', hasNumber);
    updateRequirement('reqSpecial', hasSpecial);
    
    // Calculate strength
    let strength = 0;
    if (hasLength) strength++;
    if (hasUppercase) strength++;
    if (hasLowercase) strength++;
    if (hasNumber) strength++;
    if (hasSpecial) strength++;
    
    // Update UI
    strengthBar.className = 'password-strength';
    if (password.length === 0) {
        strengthBar.style.width = '0';
        strengthText.textContent = 'Masukkan password';
    } else if (strength <= 2) {
        strengthBar.classList.add('strength-weak');
        strengthText.textContent = 'Lemah';
    } else if (strength <= 4) {
        strengthBar.classList.add('strength-medium');
        strengthText.textContent = 'Sedang';
    } else {
        strengthBar.classList.add('strength-strong');
        strengthText.textContent = 'Kuat';
    }
}

function updateRequirement(id, met) {
    const element = document.getElementById(id);
    const icon = element.querySelector('i');
    
    if (met) {
        icon.className = 'fas fa-check-circle text-green-500 mr-1';
        element.classList.add('text-green-700');
    } else {
        icon.className = 'fas fa-circle text-gray-300 mr-1';
        element.classList.remove('text-green-700');
    }
}

function isPasswordStrong(password) {
    return password.length >= 8 &&
           /[A-Z]/.test(password) &&
           /[a-z]/.test(password) &&
           /[0-9]/.test(password) &&
           /[!@#$%^&*]/.test(password);
}

function checkPasswordMatch() {
    const newPass = document.getElementById('new_password').value;
    const confirmPass = document.getElementById('new_password_confirmation').value;
    const messageEl = document.getElementById('passwordMatchMessage');
    
    if (confirmPass.length === 0) {
        messageEl.innerHTML = '';
        return;
    }
    
    if (newPass === confirmPass) {
        messageEl.innerHTML = '<span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>Password cocok</span>';
    } else {
        messageEl.innerHTML = '<span class="text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>Password tidak cocok</span>';
    }
}

function resetProfileForm() {
    document.getElementById('profileForm').reset();
}

function resetPasswordForm() {
    document.getElementById('passwordForm').reset();
    document.getElementById('passwordStrength').className = 'password-strength';
    document.getElementById('strengthText').textContent = 'Masukkan password';
    document.getElementById('passwordMatchMessage').innerHTML = '';
    
    // Reset requirement indicators
    ['reqLength', 'reqUppercase', 'reqLowercase', 'reqNumber', 'reqSpecial'].forEach(id => {
        const element = document.getElementById(id);
        const icon = element.querySelector('i');
        icon.className = 'fas fa-circle text-gray-300 mr-1';
        element.classList.remove('text-green-700');
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Check if there's any message from session
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
    @endif
    
    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false
    });
    @endif
});
</script>
@endpush
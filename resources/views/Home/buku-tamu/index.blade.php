@extends('layouts.frontend')

@section('title', 'Buku Tamu - TK Harapan Bangsa 1')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-green-500 to-emerald-600 text-white py-16">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Buku Tamu</h1>
        <p class="text-xl opacity-90">Isi data kunjungan Anda ke TK Harapan Bangsa 1</p>
    </div>
</section>

<!-- Form Section -->
<section class="py-12">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">

            
            <!-- Form -->
            <div class="p-6 md:p-8">
                <form method="POST" action="{{ route('buku-tamu.store') }}" id="bukuTamuForm" onsubmit="return validateRecaptcha()">
                    @csrf
                    
                    <!-- Data Pribadi -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-user-circle text-green-600 mr-3"></i>
                            Data Pribadi
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="nama" 
                                       value="{{ old('nama') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       required>
                                @error('nama')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Instansi/Asal <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="instansi" 
                                       value="{{ old('instansi') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       placeholder="Contoh: PT. Contoh, Universitas X, dll"
                                       required>
                                @error('instansi')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Jabatan
                                </label>
                                <input type="text" 
                                       name="jabatan" 
                                       value="{{ old('jabatan') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       placeholder="Contoh: Manager, Guru, Mahasiswa, dll">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Email
                                </label>
                                <input type="email" 
                                       name="email" 
                                       value="{{ old('email') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       placeholder="email@contoh.com">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nomor Telepon/HP
                                </label>
                                <input type="tel" 
                                       name="telepon" 
                                       value="{{ old('telepon') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       placeholder="0812-3456-7890">
                                @error('telepon')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="tanggal_kunjungan" value="{{ date('Y-m-d') }}">
                    <input type="hidden" name="jam_kunjungan" value="{{ date('H:i') }}">
                    
                    <!-- Tujuan & Pesan -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-file-alt text-green-600 mr-3"></i>
                            Tujuan Kunjungan & Pesan
                        </h3>
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tujuan Kunjungan <span class="text-red-500">*</span>
                                </label>
                                <textarea name="tujuan_kunjungan" 
                                          rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                          placeholder="Jelaskan tujuan kunjungan Anda ke TK Harapan Bangsa 1"
                                          required>{{ old('tujuan_kunjungan') }}</textarea>
                                @error('tujuan_kunjungan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Pesan & Kesan (Opsional)
                                </label>
                                <textarea name="pesan_kesan" 
                                          rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                          placeholder="Tuliskan pesan atau kesan Anda terhadap TK Harapan Bangsa 1">{{ old('pesan_kesan') }}</textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Captcha -->
                    <div class="mb-8">
                        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                        @error('g-recaptcha-response')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Submit -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-end">
                        <button type="button" 
                                onclick="resetForm()"
                                class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-redo mr-2"></i> Reset Form
                        </button>
                        <button type="submit" 
                                class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-300 shadow-md hover:shadow-lg">
                            <i class="fas fa-paper-plane mr-2"></i> Kirim Data Kunjungan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Info Box -->
        <div class="mt-8 bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-200 rounded-2xl p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                Informasi Penting
            </h3>
            <ul class="space-y-2 text-gray-700">
                <li class="flex items-start">
                    <i class="fas fa-check text-green-500 mr-2 mt-1 flex-shrink-0"></i>
                    <span>Data akan diverifikasi oleh admin sebelum kunjungan</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check text-green-500 mr-2 mt-1 flex-shrink-0"></i>
                    <span>Anda akan menerima konfirmasi via email/telepon</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check text-green-500 mr-2 mt-1 flex-shrink-0"></i>
                    <span>Jam kunjungan: Senin - Jumat, 08:00 - 16:00 WIB</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check text-green-500 mr-2 mt-1 flex-shrink-0"></i>
                    <span>Harap hadir tepat waktu sesuai jadwal yang disetujui</span>
                </li>
            </ul>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<!-- Google reCAPTCHA -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<script>
    function resetForm() {
        if (confirm('Apakah Anda yakin ingin mengosongkan semua data form?')) {
            document.getElementById('bukuTamuForm').reset();
        }
    }

    function validateRecaptcha() {
        var response = grecaptcha.getResponse();
        if (response.length == 0) {
            alert('Silakan centang reCAPTCHA terlebih dahulu!');
            return false; // Mencegah form terkirim
        }
        return true; // Form boleh dikirim
    }
    
    // Set min date to today
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.querySelector('input[name="tanggal_kunjungan"]');
        if (dateInput) {
            const today = new Date().toISOString().split('T')[0];
            dateInput.min = today;
        }
    });
</script>
@endpush
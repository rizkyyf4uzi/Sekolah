@extends('layouts.admin')

@section('title', 'Edit Tahun Ajaran')

@section('breadcrumb', 'Master Data / Tahun Ajaran / Edit')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8">
            <div class="flex items-start justify-between gap-4 mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">edit_calendar</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Edit Tahun Ajaran</h2>
                        <p class="text-sm text-slate-500">Perbarui data periode akademik yang dipilih.</p>
                    </div>
                </div>

                <a href="{{ route('admin.tahun-ajaran.index') }}"
                   class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white text-slate-700 rounded-xl border border-slate-200 hover:bg-slate-50 transition-all font-bold text-sm shadow-sm">
                    <span class="material-symbols-outlined text-xl">arrow_back</span>
                    Kembali
                </a>
            </div>

            <form action="{{ route('admin.tahun-ajaran.update', $tahunAjaran) }}" method="POST" id="mainForm" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="tahun_ajaran" class="block text-sm font-bold text-slate-700 mb-2">
                            Tahun Ajaran <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">event_repeat</span>
                            <input
                                id="tahun_ajaran"
                                name="tahun_ajaran"
                                value="{{ old('tahun_ajaran', $tahunAjaran->tahun_ajaran) }}"
                                required
                                type="text"
                                placeholder="Contoh: 2024/2025"
                                @class([
                                    'w-full pl-12 pr-4 py-3 bg-slate-50 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm',
                                    'border-rose-500' => $errors->has('tahun_ajaran'),
                                    'border-slate-200' => !$errors->has('tahun_ajaran'),
                                ])
                            />
                        </div>
                        <p class="mt-2 text-[10px] text-slate-400 italic">Format: YYYY/YYYY (Misal: 2024/2025)</p>
                        @error('tahun_ajaran') <p class="text-[10px] text-rose-500 font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-bold text-slate-700 mb-2">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">calendar_today</span>
                            <input
                                id="tanggal_mulai"
                                name="tanggal_mulai"
                                value="{{ old('tanggal_mulai', $tahunAjaran->tanggal_mulai->format('Y-m-d')) }}"
                                required
                                type="date"
                                @class([
                                    'w-full pl-12 pr-4 py-3 bg-slate-50 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm',
                                    'border-rose-500' => $errors->has('tanggal_mulai'),
                                    'border-slate-200' => !$errors->has('tanggal_mulai'),
                                ])
                            />
                        </div>
                        @error('tanggal_mulai') <p class="text-[10px] text-rose-500 font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="tanggal_selesai" class="block text-sm font-bold text-slate-700 mb-2">
                            Tanggal Selesai <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">event_busy</span>
                            <input
                                id="tanggal_selesai"
                                name="tanggal_selesai"
                                value="{{ old('tanggal_selesai', $tahunAjaran->tanggal_selesai->format('Y-m-d')) }}"
                                required
                                type="date"
                                @class([
                                    'w-full pl-12 pr-4 py-3 bg-slate-50 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm',
                                    'border-rose-500' => $errors->has('tanggal_selesai'),
                                    'border-slate-200' => !$errors->has('tanggal_selesai'),
                                ])
                            />
                        </div>
                        @error('tanggal_selesai') <p class="text-[10px] text-rose-500 font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-3">
                            Semester <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="relative flex items-center p-4 border rounded-xl cursor-pointer hover:bg-slate-50 transition-all"
                                   for="semester_ganjil">
                                <input
                                    id="semester_ganjil"
                                    class="w-4 h-4 text-primary border-slate-300 focus:ring-primary/20"
                                    name="semester"
                                    type="radio"
                                    value="Ganjil"
                                    {{ old('semester', $tahunAjaran->semester) == 'Ganjil' ? 'checked' : '' }}
                                    required
                                />
                                <div class="ml-4">
                                    <span class="block text-sm font-bold text-slate-800">Ganjil</span>
                                    <span class="block text-xs text-slate-500">Semester 1</span>
                                </div>
                            </label>

                            <label class="relative flex items-center p-4 border rounded-xl cursor-pointer hover:bg-slate-50 transition-all"
                                   for="semester_genap">
                                <input
                                    id="semester_genap"
                                    class="w-4 h-4 text-primary border-slate-300 focus:ring-primary/20"
                                    name="semester"
                                    type="radio"
                                    value="Genap"
                                    {{ old('semester', $tahunAjaran->semester) == 'Genap' ? 'checked' : '' }}
                                    required
                                />
                                <div class="ml-4">
                                    <span class="block text-sm font-bold text-slate-800">Genap</span>
                                    <span class="block text-xs text-slate-500">Semester 2</span>
                                </div>
                            </label>
                        </div>
                        @error('semester') <p class="text-[10px] text-rose-500 font-bold mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-3">Aktifkan Periode?</label>
                        <div class="p-4 border border-slate-200 rounded-xl bg-slate-50 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-slate-500">offline_pin</span>
                                <p class="text-sm text-slate-600 font-medium">Jadikan periode ini sebagai periode aktif sistem</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_aktif" value="1" {{ old('is_aktif', $tahunAjaran->is_aktif) ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary shadow-inner rounded-full"></div>
                            </label>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label for="keterangan" class="block text-sm font-bold text-slate-700 mb-2">Keterangan (Opsional)</label>
                        <textarea
                            id="keterangan"
                            name="keterangan"
                            rows="3"
                            placeholder="Tambahkan catatan khusus periode ini..."
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm"
                        >{{ old('keterangan', $tahunAjaran->keterangan) }}</textarea>
                    </div>
                </div>

                <div class="pt-8 mt-8 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-end gap-3">
                    <a href="{{ route('admin.tahun-ajaran.index') }}"
                       class="w-full sm:w-auto px-8 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-200 transition-all text-center">
                        Batal
                    </a>
                    <button
                        type="submit"
                        class="w-full sm:w-auto px-10 py-3 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 flex items-center justify-center gap-2"
                    >
                        <span class="material-symbols-outlined text-xl">save</span>
                        <span id="btnText">Simpan Perubahan</span>
                        <span id="spinner" class="hidden">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('mainForm');
        const btnText = document.getElementById('btnText');
        const spinner = document.getElementById('spinner');
        const tahunAjaranInput = document.getElementById('tahun_ajaran');
        const tanggalMulai = document.getElementById('tanggal_mulai');
        const tanggalSelesai = document.getElementById('tanggal_selesai');

        // Form Submission Overlay
        form.addEventListener('submit', function() {
            btnText.textContent = 'Memperbarui...';
            spinner.classList.remove('hidden');
        });

        // Auto-format Tahun Ajaran
        tahunAjaranInput.addEventListener('input', function(e) {
            let value = this.value.replace(/[^0-9/]/g, '');
            if (value.length === 4 && !value.includes('/')) {
                value = value + '/';
            }
            if (value.length > 9) {
                value = value.slice(0, 9);
            }
            this.value = value;
        });

        // Date Validation
        function validateDates() {
            if (tanggalMulai.value && tanggalSelesai.value) {
                if (tanggalSelesai.value < tanggalMulai.value) {
                    tanggalSelesai.setCustomValidity('Tanggal selesai harus setelah tanggal mulai');
                } else {
                    tanggalSelesai.setCustomValidity('');
                }
            }
        }

        tanggalMulai.addEventListener('change', function() {
            tanggalSelesai.min = this.value;
            validateDates();
        });
        tanggalSelesai.addEventListener('change', validateDates);
    });
</script>
@endpush

@push('styles')
<style>
    input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(0.5);
        cursor: pointer;
    }
    .dark input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(0.8);
    }
</style>
@endpush

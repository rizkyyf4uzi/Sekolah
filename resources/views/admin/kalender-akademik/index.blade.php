@extends('layouts.admin')

@section('title', 'Kalender Akademik')
@section('breadcrumb', 'Akademik / Kalender')

@push('styles')
<style type="text/tailwindcss">
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
    }
    .calendar-day {
        min-height: 140px; /* Taller on desktop for more premium feel */
        transition: all 0.2s ease;
    }
    .calendar-day:hover {
        background-color: theme('colors.slate.50');
    }
    @media (max-width: 768px) {
        .calendar-day { 
            min-height: 70px; /* More compact on mobile */
            padding: 0.25rem !important; /* Less padding on small screens */
        }
        .calendar-day:hover {
            background-color: transparent; /* No hover effect on mobile */
        }
        .event-label { display: none; }
        .event-dot { display: block !important; margin: 0 auto; }
        .calendar-grid-header { padding-top: 0.5rem; padding-bottom: 0.5rem; font-size: 0.6rem; }
    }
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
@php
    \Carbon\Carbon::setLocale('id'); // Ensure Indonesian month names
    $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Augustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
@endphp
<div x-data="calendarModal()" x-init="init()">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <p class="text-slate-500 text-sm mt-1">Kelola agenda kegiatan sekolah dan hari libur nasional (Masehi).</p>
        </div>
        <div class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center justify-between gap-3 w-full mt-4 md:mt-0">
            <div class="flex items-center justify-between sm:justify-start gap-1 bg-white rounded-xl shadow-sm border border-slate-100 p-1 relative w-full sm:w-auto">
                <a href="{{ route('admin.kalender-akademik.index', ['year' => $prevMonth->year, 'month' => $prevMonth->month]) }}" 
                   class="p-2 hover:bg-slate-50 rounded-lg text-slate-400 transition-colors">
                    <span class="material-symbols-outlined">chevron_left</span>
                </a>
                
                <!-- Month Picker Toggle -->
                <button @click="isMonthPickerOpen = !isMonthPickerOpen" 
                        class="px-4 py-1.5 flex flex-1 sm:flex-none justify-center items-center gap-2 hover:bg-slate-50 rounded-lg transition-colors cursor-pointer">
                    <span class="font-bold text-slate-700 whitespace-nowrap">{{ $currentMonth->translatedFormat('F Y') }}</span>
                    <span class="material-symbols-outlined text-slate-400 text-sm transition-transform" :class="isMonthPickerOpen ? 'rotate-180' : ''">keyboard_arrow_down</span>
                </button>

                <!-- Month Picker Dropdown -->
                <div x-show="isMonthPickerOpen" 
                     x-cloak
                     @click.away="isMonthPickerOpen = false"
                     class="absolute top-full left-0 mt-2 w-72 bg-white rounded-2xl shadow-xl border border-slate-100 z-50 p-4"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    
                    <div class="flex items-center justify-between mb-4 px-2">
                        <button @click="pickerYear--" class="p-1 hover:bg-slate-50 rounded-lg text-slate-400">
                            <span class="material-symbols-outlined text-sm">chevron_left</span>
                        </button>
                        <span class="font-bold text-slate-800" x-text="pickerYear"></span>
                        <button @click="pickerYear++" class="p-1 hover:bg-slate-50 rounded-lg text-slate-400">
                            <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </button>
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        @foreach($months as $num => $name)
                            <button @click="selectMonth({{ $num }})" 
                                    class="py-2 text-xs font-medium rounded-xl transition-all"
                                    :class="pickerMonth == {{ $num }} && pickerYear == {{ $year }} ? 'bg-primary text-white' : 'hover:bg-primary/10 text-slate-600'">
                                {{ $name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('admin.kalender-akademik.index', ['year' => $nextMonth->year, 'month' => $nextMonth->month]) }}" 
                   class="p-2 hover:bg-slate-50 rounded-lg text-slate-400 transition-colors">
                    <span class="material-symbols-outlined">chevron_right</span>
                </a>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
                <a href="{{ route('admin.kalender-akademik.index') }}" 
                   class="w-full sm:w-auto text-center px-4 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all whitespace-nowrap">
                    Hari Ini
                </a>
                <button @click="openAddModal()"
                   class="w-full sm:w-auto justify-center px-5 py-2.5 bg-primary text-white rounded-xl font-bold text-sm shadow-lg shadow-primary/20 flex items-center gap-2 hover:bg-primary/90 transition-all whitespace-nowrap">
                    <span class="material-symbols-outlined text-lg">add</span>
                    Tambah Agenda
                </button>
            </div>
        </div>
    </div>

    <!-- Category legend remains same but ensured it matches model icons -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 mb-6 flex flex-wrap items-center gap-6">
        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mr-2">Legend:</span>
        @foreach($daftarKategori as $key => $kat)
            @php
                $dotColor = match($key) {
                    'Libur Nasional' => 'bg-red-500',
                    'Ujian' => 'bg-primary',
                    'Kegiatan Sekolah' => 'bg-blue-500',
                    'Rapat Guru' => 'bg-orange-500',
                    default => 'bg-slate-400'
                };
            @endphp
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full {{ $dotColor }}"></div>
                <span class="text-sm font-medium text-slate-700">{{ str_replace(['🔴 ', '🟣 ', '🔵 ', '🟠 ', '⚪ '], '', $kat['label']) }}</span>
            </div>
        @endforeach
    </div>

    <!-- Calendar Grid remains same -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <!-- ... existing grid code ... -->
        <div class="calendar-grid border-b border-slate-100 bg-slate-50/50">
            @php
                $days = [
                    ['full' => 'Minggu', 'short' => 'Min'],
                    ['full' => 'Senin', 'short' => 'Sen'],
                    ['full' => 'Selasa', 'short' => 'Sel'],
                    ['full' => 'Rabu', 'short' => 'Rab'],
                    ['full' => 'Kamis', 'short' => 'Kam'],
                    ['full' => 'Jumat', 'short' => 'Jum'],
                    ['full' => 'Sabtu', 'short' => 'Sab'],
                ];
            @endphp
            @foreach($days as $day)
                <div class="calendar-grid-header py-2 md:py-4 text-center text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-widest">
                    <span class="hidden md:inline">{{ $day['full'] }}</span>
                    <span class="md:hidden">{{ $day['short'] }}</span>
                </div>
            @endforeach
        </div>
        
        <div class="calendar-grid">
            @php
                $prevMonthDays = $currentMonth->copy()->subMonth()->daysInMonth;
                $startDay = $prevMonthDays - $prevDays + 1;
                $today = now()->startOfDay();
            @endphp

            {{-- Previous Month Days --}}
            @for($i = 0; $i < $prevDays; $i++)
                <div class="calendar-day p-2 border-b border-r border-slate-100 bg-slate-50/30">
                    <span class="text-slate-300 font-medium text-sm">{{ $startDay + $i }}</span>
                </div>
            @endfor

            {{-- Current Month Days --}}
            @for($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $date = \Carbon\Carbon::create($year, $month, $day);
                    $isToday = $date->isSameDay($today);
                    $isSunday = $date->dayOfWeek === 0;
                    $dayEvents = $eventsByDay[$day] ?? [];
                @endphp
                <div class="calendar-day p-2 border-b border-r border-slate-100 {{ $isToday ? 'bg-primary/5' : '' }}">
                    <span class="{{ $isSunday ? 'text-red-500' : 'text-slate-700' }} font-bold text-sm">{{ $day }}</span>
                    <div class="mt-1 flex flex-col gap-1">
                        @foreach($dayEvents as $event)
                            @php $classes = $event->tailwindClasses; @endphp
                            <div class="event-label {{ $classes['bg'] }} {{ $classes['text'] }} text-[10px] px-1.5 py-0.5 rounded font-bold truncate cursor-pointer" 
                                 @click="openEditModal({{ json_encode($event) }})"
                                 title="{{ $event->judul }}">
                                {{ $event->judul }}
                            </div>
                            <div class="event-dot hidden {{ $classes['dot'] }} w-1.5 h-1.5 rounded-full" title="{{ $event->judul }}"></div>
                        @endforeach
                    </div>
                </div>
            @endfor

            {{-- Next Month Days --}}
            @php
                $remainingCells = 7 - (($prevDays + $daysInMonth) % 7);
                if ($remainingCells == 7) $remainingCells = 0;
            @endphp
            @for($i = 1; $i <= $remainingCells; $i++)
                <div class="calendar-day p-2 border-b {{ ($prevDays + $daysInMonth + $i) % 7 != 0 ? 'border-r' : '' }} border-slate-100 bg-slate-50/30">
                    <span class="text-slate-300 font-medium text-sm">{{ $i }}</span>
                </div>
            @endfor
        </div>
    </div>

    {{-- Agenda List remains same --}}
    @php
        $allEvents = \App\Models\KalenderAkademik::inMonth($year, $month)
            ->where('is_aktif', true)
            ->orderBy('tanggal_mulai')
            ->get();
    @endphp

    @if($allEvents->count() > 0)
    <div class="mt-8">
        <h3 class="text-lg font-bold text-slate-800 mb-4 px-2">Daftar Agenda {{ $currentMonth->translatedFormat('F Y') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($allEvents as $event)
                @php $classes = $event->tailwindClasses; @endphp
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group">
                    <div class="flex items-start justify-between mb-3">
                        <div class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider {{ $classes['bg'] }} {{ $classes['text'] }}">
                            {{ $event->kategori }}
                        </div>
                        <div class="flex gap-2 md:gap-1 opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity">
                            <button @click="openEditModal({{ json_encode($event) }})" class="p-1.5 md:p-1 text-slate-400 hover:text-primary transition-colors bg-slate-50 md:bg-transparent rounded-lg md:rounded-none">
                                <span class="material-symbols-outlined text-[16px] md:text-sm">edit</span>
                            </button>
                            <form action="{{ route('admin.kalender-akademik.destroy', $event) }}" method="POST" onsubmit="return confirm('Hapus agenda ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 md:p-1 text-slate-400 hover:text-red-500 transition-colors bg-slate-50 md:bg-transparent rounded-lg md:rounded-none">
                                    <span class="material-symbols-outlined text-[16px] md:text-sm">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    <h4 class="font-bold text-slate-800 mb-1">{{ $event->judul }}</h4>
                    <p class="text-xs text-slate-500 line-clamp-2 mb-3">{{ $event->deskripsi ?: 'Tidak ada deskripsi.' }}</p>
                    <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        <span class="material-symbols-outlined text-xs">calendar_month</span>
                        {{ $event->tanggal_mulai->translatedFormat('d M Y') }}
                        @if($event->tanggal_selesai)
                            - {{ $event->tanggal_selesai->translatedFormat('d M Y') }}
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Updated Modal Agenda with improved Jenis Agenda select -->
    <div x-show="isOpen" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]"
             @click.away="closeModal()"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-white sticky top-0 z-10">
                <div>
                    <h2 class="text-xl font-bold text-slate-900" x-text="isEdit ? 'Edit Agenda' : 'Tambah Agenda Baru'"></h2>
                    <p class="text-xs text-slate-500 mt-0.5">Lengkapi informasi agenda untuk kalender akademik.</p>
                </div>
                <button @click="closeModal()" class="p-2 hover:bg-slate-100 rounded-xl transition-colors text-slate-400">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="overflow-y-auto">
                <form :action="formAction" method="POST" class="p-8 space-y-6">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 ml-1">Judul Agenda <span class="text-red-500">*</span></label>
                        <input type="text" name="judul" x-model="formData.judul" required
                               class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm placeholder:text-slate-400" 
                               placeholder="Masukkan judul agenda (misal: Ujian Akhir Semester)">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 ml-1">Jenis Agenda <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="kategori" x-model="formData.kategori" required
                                        class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm appearance-none bg-white font-medium">
                                    <option value="" disabled>Pilih jenis agenda</option>
                                    @foreach($daftarKategori as $key => $kat)
                                        <option value="{{ $key }}">{{ $kat['label'] }}</option>
                                    @endforeach
                                </select>
                                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">unfold_more</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 ml-1">Keterangan</label>
                            <input type="text" name="deskripsi" x-model="formData.deskripsi"
                                   class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm placeholder:text-slate-400" 
                                   placeholder="Detail singkat (opsional)">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 ml-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="date" name="tanggal_mulai" x-model="formData.tanggal_mulai" required
                                       class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm">
                                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 text-lg">calendar_today</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 ml-1">Tanggal Selesai</label>
                            <div class="relative">
                                <input type="date" name="tanggal_selesai" x-model="formData.tanggal_selesai"
                                       class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm">
                                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 text-lg">event_available</span>
                            </div>
                            <p class="text-[10px] text-slate-400 ml-1">Kosongkan jika hanya 1 hari.</p>
                        </div>
                    </div>

                    <div class="p-6 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 bg-slate-50/50 mt-4 -mx-8 -mb-8">
                        <button type="button" @click="closeModal()" class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-200 rounded-xl transition-all">
                            Batal
                        </button>
                        <button type="submit" class="w-full sm:w-auto justify-center px-8 py-2.5 text-sm font-bold text-white bg-primary rounded-xl shadow-lg shadow-primary/30 hover:bg-primary/90 transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">save</span>
                            <span x-text="isEdit ? 'Perbarui Agenda' : 'Simpan Agenda'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function calendarModal() {
        return {
            isOpen: false,
            isEdit: false,
            isMonthPickerOpen: false,
            pickerYear: {{ $year }},
            pickerMonth: {{ $month }},
            formAction: "{{ route('admin.kalender-akademik.store') }}",
            formData: {
                id: '',
                judul: '',
                kategori: '',
                deskripsi: '',
                tanggal_mulai: '',
                tanggal_selesai: ''
            },
            init() {
                // Initialize if needed
            },
            selectMonth(m) {
                const url = new URL(window.location.href);
                url.searchParams.set('month', m);
                url.searchParams.set('year', this.pickerYear);
                window.location.href = url.toString();
            },
            openAddModal() {
                this.isEdit = false;
                this.formAction = "{{ route('admin.kalender-akademik.store') }}";
                this.formData = {
                    id: '',
                    judul: '',
                    kategori: '',
                    deskripsi: '',
                    tanggal_mulai: '',
                    tanggal_selesai: ''
                };
                this.isOpen = true;
            },
            openEditModal(event) {
                this.isEdit = true;
                // Use template literal for dynamic route
                this.formAction = `/admin/kalender-akademik/${event.id}`;
                this.formData = {
                    id: event.id,
                    judul: event.judul,
                    kategori: event.kategori,
                    deskripsi: event.deskripsi || '',
                    tanggal_mulai: this.formatDate(event.tanggal_mulai),
                    tanggal_selesai: event.tanggal_selesai ? this.formatDate(event.tanggal_selesai) : ''
                };
                this.isOpen = true;
            },
            closeModal() {
                this.isOpen = false;
            },
            formatDate(dateStr) {
                if (!dateStr) return '';
                // Handle carbon format or ISO string
                const d = new Date(dateStr);
                return d.toISOString().split('T')[0];
            }
        }
    }
</script>
@endpush

<!-- Card Total -->
<div class="bg-white dark:bg-slate-800 rounded-[2rem] border border-slate-100 dark:border-slate-700 p-6 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-3xl font-bold">groups</span>
        </div>
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1.5">Total Guru</p>
            <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight leading-none" id="stat-total">{{ $gurus->total() ?? 0 }}</h3>
        </div>
    </div>
    <div class="mt-4 pt-4 border-t border-slate-50 dark:border-slate-700/50 flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
        <span class="material-symbols-outlined text-sm">info</span>
        Semua guru & staff terdaftar
    </div>
</div>

<!-- Card Guru -->
<div class="bg-white dark:bg-slate-800 rounded-[2rem] border border-slate-100 dark:border-slate-700 p-6 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-3xl font-bold">co_present</span>
        </div>
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1.5">Jumlah Guru</p>
            <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight leading-none" id="stat-guru">
                {{ isset($gurus) ? $gurus->where('jabatan', 'guru')->count() : 0 }}
            </h3>
        </div>
    </div>
    <div class="mt-4 pt-4 border-t border-slate-50 dark:border-slate-700/50 flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
        <span class="material-symbols-outlined text-sm">verified</span>
        Guru pengajar aktif
    </div>
</div>

<!-- Card Staff -->
<div class="bg-white dark:bg-slate-800 rounded-[2rem] border border-slate-100 dark:border-slate-700 p-6 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-3xl font-bold">badge</span>
        </div>
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1.5">Jumlah Staff</p>
            <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight leading-none" id="stat-staff">
                {{ isset($gurus) ? $gurus->where('jabatan', 'staff')->count() : 0 }}
            </h3>
        </div>
    </div>
    <div class="mt-4 pt-4 border-t border-slate-50 dark:border-slate-700/50 flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
        <span class="material-symbols-outlined text-sm">work</span>
        Staff administrasi
    </div>
</div>
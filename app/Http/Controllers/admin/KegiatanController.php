<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class KegiatanController extends Controller
{
    public function index()
    {
        return view('admin.kegiatan.index');
    }

    public function create()
    {
        return view('admin.kegiatan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kegiatan'   => 'required|string|max:255',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'waktu_mulai'     => 'nullable',
            'waktu_selesai'   => 'nullable',
            'lokasi'          => 'required|string|max:255',
            'kategori'        => 'required|string|max:100',
            'deskripsi'       => 'nullable|string',
            'banner'          => 'nullable|image|max:5120',
            'is_published'    => 'boolean'
        ]);

        if ($request->hasFile('banner')) {
            $validated['banner_path'] = $request->file('banner')->store('kegiatan', 'public');
        }

        Kegiatan::create($validated);

        return redirect()->route('admin.kegiatan.index')
            ->with('success', 'Kegiatan berhasil ditambahkan!');
    }

    public function show(Kegiatan $kegiatan)
    {
        return view('admin.kegiatan.show', compact('kegiatan'));
    }

    public function edit(Kegiatan $kegiatan)
    {
        return view('admin.kegiatan.edit', compact('kegiatan'));
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $validated = $request->validate([
            'nama_kegiatan'   => 'required|string|max:255',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'waktu_mulai'     => 'nullable',
            'waktu_selesai'   => 'nullable',
            'lokasi'          => 'required|string|max:255',
            'kategori'        => 'required|string|max:100',
            'deskripsi'       => 'nullable|string',
            'banner'          => 'nullable|image|max:5120',
            'is_published'    => 'boolean'
        ]);

        if ($request->hasFile('banner')) {
            // Hapus yang lama
            if ($kegiatan->banner_path) {
                Storage::disk('public')->delete($kegiatan->banner_path);
            }
            $validated['banner_path'] = $request->file('banner')->store('kegiatan', 'public');
        }

        $kegiatan->update($validated);

        return redirect()->route('admin.kegiatan.index')
            ->with('success', 'Kegiatan berhasil diperbarui!');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();

        return redirect()->route('admin.kegiatan.index')
            ->with('success', 'Kegiatan berhasil dihapus!');
    }

    public function togglePublish(Kegiatan $kegiatan)
    {
        $kegiatan->update(['is_published' => !$kegiatan->is_published]);

        return back()->with('success', 'Status kegiatan berhasil diubah!');
    }
}
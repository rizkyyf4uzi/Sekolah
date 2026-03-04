<?php
// app/Http/Controllers/Admin/GaleriController.php
namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Galeri;
use App\Models\GambarGaleri; // Model baru untuk multiple images
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GaleriController extends Controller
{
    public function index()
    {
        return view('admin.galeri.index');
    }

    public function create()
    {
        return view('admin.galeri.create');
    }

    public function store(Request $request)
    {
        // Untuk AJAX (fetch dari JS), validasi error otomatis return JSON 422
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'kategori'     => 'required|string|max:100',
            'tanggal'      => 'required|date',
            'lokasi'       => 'nullable|string|max:255',
            'is_published' => 'boolean',
            'gambar'       => 'required|array|min:1',
            'gambar.*'     => 'image|mimes:jpg,jpeg,png,gif|max:5120',
        ]);

        DB::beginTransaction();

        try {
            $galeri = Galeri::create([
                'judul'        => $request->judul,
                'deskripsi'    => $request->deskripsi,
                'kategori'     => $request->kategori,
                'tanggal'      => $request->tanggal,
                'lokasi'       => $request->lokasi ?? 'TK Harapan Bangsa 1',
                'user_id'      => auth()->id(),
                'is_published' => $request->boolean('is_published'),
            ]);

            if ($request->hasFile('gambar')) {
                foreach ($request->file('gambar') as $index => $file) {
                    $fileName = time() . '_' . $index . '_' . $file->getClientOriginalName();
                    $path     = $file->storeAs('galeri', $fileName, 'public');

                    $galeri->gambar()->create([
                        'path'           => $path,
                        'nama_file'      => $file->getClientOriginalName(),
                        'nama_file_asli' => $fileName,
                        'ukuran'         => $file->getSize(),
                        'mime_type'      => $file->getMimeType(),
                        'urutan'         => $index,
                    ]);
                }
            }

            DB::commit();

            $successMsg = 'Galeri berhasil ditambahkan dengan ' . $galeri->gambar->count() . ' gambar!';

            // Jika request dari fetch (AJAX), return JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success'  => true,
                    'message'  => $successMsg,
                    'redirect' => route('admin.galeri.index'),
                ]);
            }

            return redirect()->route('admin.galeri.index')->with('success', $successMsg);

        } catch (\Exception $e) {
            DB::rollback();

            if ($request->hasFile('gambar')) {
                foreach ($request->file('gambar') as $file) {
                    Storage::disk('public')->delete('galeri/' . $file->hashName());
                }
            }

            // Jika request dari fetch (AJAX), return JSON error
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Galeri $galeri)
    {
        $galeri->load('gambar', 'user');
        return view('admin.galeri.show', compact('galeri'));
    }

    public function edit(Galeri $galeri)
    {
        $galeri->load('gambar');
        return view('admin.galeri.edit', compact('galeri'));
    }

    public function update(Request $request, Galeri $galeri)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'required|string|max:100',
            'gambar.*' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
            'tanggal' => 'required|date',
            'lokasi' => 'nullable|string|max:255',
            'is_published' => 'boolean',
            'hapus_gambar' => 'nullable|array', // Array ID gambar yang akan dihapus
            'hapus_gambar.*' => 'exists:gambar_galeri,id'
        ]);

        DB::beginTransaction();
        
        try {
            // 1. HAPUS GAMBAR YANG DIPILIH USER
            if ($request->filled('hapus_gambar')) {
                $gambarDihapus = GambarGaleri::whereIn('id', $request->hapus_gambar)
                    ->where('galeri_id', $galeri->id)
                    ->get();
                
                foreach ($gambarDihapus as $gambar) {
                    // Hapus file dari storage
                    Storage::disk('public')->delete($gambar->path);
                    // Hapus record
                    $gambar->delete();
                }
            }
            
            // 2. UPDATE DATA GALERI
            $galeri->update([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'kategori' => $request->kategori,
                'tanggal' => $request->tanggal,
                'lokasi' => $request->lokasi,
                'is_published' => $request->boolean('is_published')
            ]);

            // 3. TAMBAHKAN GAMBAR BARU (TIDAK MENGHAPUS YANG LAMA)
            if ($request->hasFile('gambar')) {
                // Dapatkan urutan tertinggi saat ini
                $urutanTerakhir = $galeri->gambar()->max('urutan') ?? -1;
                
                foreach ($request->file('gambar') as $index => $file) {
                    // Generate nama file unik
                    $fileName = time() . '_' . ($urutanTerakhir + $index + 1) . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('galeri', $fileName, 'public');
                    
                    $galeri->gambar()->create([
                        'path' => $path,
                        'nama_file' => $file->getClientOriginalName(),
                        'nama_file_asli' => $fileName,
                        'ukuran' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'urutan' => $urutanTerakhir + $index + 1
                    ]);
                }
            }
            
            // 4. REORDER URUTAN GAMBAR (opsional)
            if ($request->filled('urutan')) {
                foreach ($request->urutan as $index => $gambarId) {
                    GambarGaleri::where('id', $gambarId)
                        ->where('galeri_id', $galeri->id)
                        ->update(['urutan' => $index]);
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.galeri.index')
                ->with('success', 'Galeri berhasil diperbarui! ' . 
                    ($request->filled('hapus_gambar') ? count($request->hapus_gambar) . ' gambar dihapus. ' : '') .
                    ($request->hasFile('gambar') ? count($request->file('gambar')) . ' gambar ditambahkan.' : ''));
            
        } catch (\Exception $e) {
            DB::rollback();
            
            // Hapus file yang baru diupload jika error
            if ($request->hasFile('gambar')) {
                foreach ($request->file('gambar') as $file) {
                    Storage::disk('public')->delete('galeri/' . $file->hashName());
                }
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Galeri $galeri)
    {
        // Hapus semua gambar dari storage
        foreach ($galeri->gambar as $gambar) {
            Storage::disk('public')->delete($gambar->path);
            $gambar->delete();
        }

        $galeri->delete();

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Galeri berhasil dihapus!');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:galeris,id'
        ]);

        $galeris = Galeri::whereIn('id', $request->ids)->with('gambar')->get();

        foreach ($galeris as $galeri) {
            // Hapus semua gambar
            foreach ($galeri->gambar as $gambar) {
                Storage::disk('public')->delete($gambar->path);
                $gambar->delete();
            }
            $galeri->delete();
        }

        return response()->json([
            'success' => true,
            'message' => count($request->ids) . ' galeri berhasil dihapus!'
        ]);
    }

    public function togglePublish(Galeri $galeri)
    {
        $galeri->update(['is_published' => !$galeri->is_published]);

        return back()->with('success', 'Status galeri berhasil diubah!');
    }

    // Method baru untuk menghapus gambar individual
    public function destroyGambar($id)
    {
        try {
            $gambar = GambarGaleri::findOrFail($id);
            
            // Hapus file dari storage
            if (Storage::disk('public')->exists($gambar->path)) {
                Storage::disk('public')->delete($gambar->path);
            }
            
            // Hapus record
            $gambar->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Gambar berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus gambar: ' . $e->getMessage()
            ], 500);
        }
    }

    // Method untuk mengupdate urutan gambar
    public function updateUrutan(Request $request, Galeri $galeri)
    {
        $request->validate([
            'urutan' => 'required|array',
            'urutan.*' => 'exists:gambar_galeri,id'
        ]);

        foreach ($request->urutan as $index => $gambarId) {
            GambarGaleri::where('id', $gambarId)
                ->where('galeri_id', $galeri->id)
                ->update(['urutan' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
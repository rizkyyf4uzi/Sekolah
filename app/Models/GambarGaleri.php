<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GambarGaleri extends Model
{
    protected $table = 'gambar_galeri';
    
    // Constants
    const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    const MAX_FILE_SIZE = 5120; // KB
    
    protected $fillable = [
        'galeri_id',
        'path',
        'nama_file',
        'nama_file_asli',
        'ukuran',
        'urutan',
        'caption',
        'mime_type'
    ];

    protected $appends = [
        'url',
        'thumbnail_url',
        'ukuran_formatted',
        'file_exists',
        'ekstensi',
        'is_image',
        'badge_color',
        'nama_tanpa_ekstensi' // ✅ Tambahkan ini
    ];

    protected static function boot()
    {
        parent::boot();

        // HAPUS FILE FISIK saat record dihapus
        static::deleting(function ($gambar) {
            if ($gambar->path && Storage::disk('public')->exists($gambar->path)) {
                Storage::disk('public')->delete($gambar->path);
                
                // Hapus juga thumbnail jika ada
                $thumbnailPath = 'thumbnails/' . basename($gambar->path);
                if (Storage::disk('public')->exists($thumbnailPath)) {
                    Storage::disk('public')->delete($thumbnailPath);
                }
            }
        });

        // Set urutan otomatis jika tidak ada
        static::creating(function ($gambar) {
            if (is_null($gambar->urutan)) {
                $gambar->urutan = GambarGaleri::where('galeri_id', $gambar->galeri_id)->max('urutan') + 1 ?: 0;
            }
        });

        // Set mime_type otomatis
        static::saving(function ($gambar) {
            if (empty($gambar->mime_type) && $gambar->path) {
                $path = Storage::disk('public')->path($gambar->path);
                if (file_exists($path)) {
                    $gambar->mime_type = mime_content_type($path);
                }
            }
        });
    }

    // ============= RELASI =============
    public function galeri()
    {
        return $this->belongsTo(Galeri::class);
    }

    // ============= SCOPES =============
    public function scopePublished($query)
    {
        return $query->whereHas('galeri', function($q) {
            $q->where('is_published', true);
        });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan', 'asc');
    }

    public function scopeImagesOnly($query)
    {
        return $query->where('mime_type', 'like', 'image/%');
    }

    // ============= ACCESSORS =============
    // app/Models/GambarGaleri.php

    public function getUrlAttribute()
    {
        // Pastikan mengembalikan string URL, bukan array/object
        if (empty($this->path)) {
            return asset('images/no-image.jpg');
        }
        
        // Hapus 'public/' dari path jika ada
        $path = str_replace('public/', '', $this->path);
        
        // Kembalikan URL lengkap
        return asset('storage/' . $path);
    }

    public function getThumbnailUrlAttribute()
    {
        $thumbnailPath = 'thumbnails/' . basename($this->path);
        
        if (Storage::disk('public')->exists($thumbnailPath)) {
            return Storage::url($thumbnailPath);
        }
        
        return $this->url;
    }

    public function getUkuranFormattedAttribute()
    {
        $bytes = $this->ukuran ?? 0;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * ✅ PERBAIKAN: Ambil dari attributes array, BUKAN dari property
     */
    public function getEkstensiAttribute()
    {
        $namaFile = $this->attributes['nama_file'] ?? '';
        return strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
    }

    public function getIsImageAttribute()
    {
        return in_array($this->ekstensi, self::ALLOWED_EXTENSIONS);
    }

    /**
     * ✅ PERBAIKAN: Ganti nama accessor dan ambil dari attributes
     */
    public function getNamaTanpaEkstensiAttribute()
    {
        $namaFile = $this->attributes['nama_file'] ?? '';
        return pathinfo($namaFile, PATHINFO_FILENAME);
    }

    public function getFileExistsAttribute()
    {
        return Storage::disk('public')->exists($this->path);
    }

    public function getBadgeColorAttribute()
    {
        return match($this->ekstensi) {
            'jpg', 'jpeg', 'png' => 'blue',
            'gif' => 'green',
            'webp' => 'purple',
            default => 'gray'
        };
    }

    // ============= CUSTOM METHODS =============
    public function moveUp()
    {
        $previous = self::where('galeri_id', $this->galeri_id)
            ->where('urutan', '<', $this->urutan)
            ->orderBy('urutan', 'desc')
            ->first();
            
        if ($previous) {
            $temp = $this->urutan;
            $this->urutan = $previous->urutan;
            $previous->urutan = $temp;
            
            $this->save();
            $previous->save();
        }
        
        return $this;
    }

    public function moveDown()
    {
        $next = self::where('galeri_id', $this->galeri_id)
            ->where('urutan', '>', $this->urutan)
            ->orderBy('urutan', 'asc')
            ->first();
            
        if ($next) {
            $temp = $this->urutan;
            $this->urutan = $next->urutan;
            $next->urutan = $temp;
            
            $this->save();
            $next->save();
        }
        
        return $this;
    }

    public function duplicate($newGaleriId)
    {
        $newPath = 'galeri/' . uniqid() . '_' . $this->attributes['nama_file'];
        
        if (Storage::disk('public')->copy($this->path, $newPath)) {
            $duplicate = $this->replicate()->fill([
                'galeri_id' => $newGaleriId,
                'path' => $newPath,
                'urutan' => null
            ]);
            
            $duplicate->save();
            return $duplicate;
        }
        
        return false;
    }

    /**
     * Regenerate thumbnail (jika pakai image intervention)
     */
    public function regenerateThumbnail()
    {
        // Implementasi dengan Image Intervention
        // if ($this->is_image && $this->file_exists) {
        //     // Generate thumbnail code here
        // }
        
        return $this;
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kegiatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kegiatan',
        'slug',
        'tanggal_mulai',
        'tanggal_selesai',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'kategori',
        'banner_path',
        'deskripsi',
        'is_published',
        'user_id'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'waktu_mulai' => 'datetime:H:i',
        'waktu_selesai' => 'datetime:H:i',
        'is_published' => 'boolean'
    ];

    public function getBannerUrlAttribute()
    {
        return $this->banner_path ? asset('storage/' . $this->banner_path) : asset('images/no-image.jpg');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kegiatan) {
            if (!$kegiatan->slug) {
                $kegiatan->slug = Str::slug($kegiatan->nama_kegiatan);
            }
            if (!isset($kegiatan->user_id) && auth()->check()) {
                $kegiatan->user_id = auth()->id();
            }
        });

        static::updating(function ($kegiatan) {
            if ($kegiatan->isDirty('nama_kegiatan')) {
                $kegiatan->slug = Str::slug($kegiatan->nama_kegiatan);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
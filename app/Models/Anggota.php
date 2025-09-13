<?php

namespace App\Models;

use App\JenisKelamin;
use App\StatusAnggota;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Anggota extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'name',
        'alias',
        'jenis_kelamin',
        'status',
        'lokasi_id',
        'jabatan_id',
        'no_telp',
        'alamat',
        'aktif_arisan',
    ];

    protected $casts = [
        'jenis_kelamin' => JenisKelamin::class,
        'status' => StatusAnggota::class,
        'aktif_arisan' => 'boolean',
    ];

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'lokasi_id');
    }

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($anggota) {
            if ($anggota->image) {
                Storage::disk('local')->delete($anggota->image);
            }
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalArisan extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'acara',
        'anggota_id',
        'lokasi_id',
        'deskripsi',
        'alamat_rumah',
        'titik_alamat_rumah',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'lokasi_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KocokArisan extends Model
{
    use HasFactory;

    protected $table = 'kocok_arisan';

    protected $fillable = [
        'periode',
        'bulan',
        'tahun',
        'anggota_id',
        'nama_anggota',
        'status',
    ];

    protected $casts = [
        'periode' => 'integer',
        'tahun' => 'integer',
        'anggota_id' => 'integer',
    ];

    /**
     * Relasi ke model Anggota
     */
    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    /**
     * Accessor untuk mendapatkan nama anggota dari relasi
     */
    public function getNamaAnggotaAttribute()
    {
        return $this->anggota ? $this->anggota->nama : $this->attributes['nama_anggota'] ?? 'Tidak tersedia';
    }

    /**
     * Accessor untuk format bulan Indonesia
     */
    public function getBulanFormatAttribute()
    {
        $bulanMap = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
            '1' => 'Januari', '2' => 'Februari', '3' => 'Maret', '4' => 'April',
            '5' => 'Mei', '6' => 'Juni', '7' => 'Juli', '8' => 'Agustus',
            '9' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
        ];
        
        return $bulanMap[$this->bulan] ?? $this->bulan;
    }

    /**
     * Scope untuk filter berdasarkan tahun
     */
    public function scopeByYear($query, $year)
    {
        return $query->where('tahun', $year);
    }

    /**
     * Scope untuk filter berdasarkan bulan
     */
    public function scopeByMonth($query, $month)
    {
        return $query->where('bulan', $month);
    }

    /**
     * Scope untuk mendapatkan pemenang yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}

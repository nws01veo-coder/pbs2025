<?php

namespace App\Exports;

use App\Models\KasKeluar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KasKeluarExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return KasKeluar::with('anggota')->get()->map(function ($kasKeluar) {
            return [
                'id' => $kasKeluar->id,
                'tanggal' => $kasKeluar->tanggal->format('d/m/Y'),
                'deskripsi' => $kasKeluar->deskripsi,
                'jumlah' => number_format($kasKeluar->jumlah, 0, ',', '.'),
                'anggota' => $kasKeluar->anggota->name ?? '',
                'created_at' => $kasKeluar->created_at?->format('Y-m-d H:i:s'),
                'updated_at' => $kasKeluar->updated_at?->format('Y-m-d H:i:s'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tanggal',
            'Deskripsi',
            'Jumlah',
            'Anggota',
            'Created At',
            'Updated At',
        ];
    }
}
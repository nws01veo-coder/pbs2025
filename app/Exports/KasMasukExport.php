<?php

namespace App\Exports;

use App\Models\KasMasuk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KasMasukExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return KasMasuk::all()->map(function ($kasMasuk) {
            return [
                'id' => $kasMasuk->id,
                'tanggal' => $kasMasuk->tanggal->format('d/m/Y'),
                'deskripsi' => $kasMasuk->deskripsi,
                'jumlah' => number_format($kasMasuk->jumlah, 0, ',', '.'),
                'created_at' => $kasMasuk->created_at?->format('Y-m-d H:i:s'),
                'updated_at' => $kasMasuk->updated_at?->format('Y-m-d H:i:s'),
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
            'Created At',
            'Updated At',
        ];
    }
}
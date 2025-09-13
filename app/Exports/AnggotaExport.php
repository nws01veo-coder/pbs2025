<?php

namespace App\Exports;

use App\Models\Anggota;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AnggotaExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Anggota::with('jabatan', 'lokasi')->get()->map(function ($anggota) {
            return [
                'id' => $anggota->id,
                'name' => $anggota->name,
                'jabatan' => $anggota->jabatan->name ?? '',
                'lokasi' => $anggota->lokasi->name ?? '',
                'jenis_kelamin' => $anggota->jenis_kelamin->value,
                'no_telp' => $anggota->no_telp,
                'alamat' => $anggota->alamat,
                'status' => $anggota->status->value,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Jabatan',
            'Lokasi',
            'Jenis Kelamin',
            'No Telp',
            'Alamat',
            'Status',
        ];
    }
}

<?php

namespace App\Imports;

use App\Models\Anggota;
use App\Models\Jabatan;
use App\Models\Location;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AnggotaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Anggota([
            'name' => $row['name'],
            'jabatan_id' => Jabatan::where('name', $row['jabatan'])->first()->id ?? null,
            'lokasi_id' => Location::where('name', $row['lokasi'])->first()->id ?? null,
            'jenis_kelamin' => $row['jenis_kelamin'],
            'no_telp' => $row['no_telp'],
            'alamat' => $row['alamat'],
            'status' => $row['status'],
            'image' => $row['image'],
        ]);
    }
}

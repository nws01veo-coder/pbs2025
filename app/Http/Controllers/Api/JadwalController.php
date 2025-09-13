<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JadwalArisan;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        try {
            $jadwal = JadwalArisan::with('anggota', 'lokasi')->get();

            $formattedJadwal = $jadwal->map(function ($item) {
                return [
                    'id' => $item->id,
                    'tanggal' => $item->tanggal->format('d F Y'),
                    'acara' => $item->acara,
                    'anggota' => $item->anggota->name,
                    'lokasi' => $item->lokasi->name,
                    'deskripsi' => $item->deskripsi,
                    'alamat_rumah' => $item->alamat_rumah,
                    'titik_alamat_rumah' => $item->titik_alamat_rumah,
                ];
            });

            return response()->json([
                'data' => $formattedJadwal,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch jadwal data.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KasKeluar;
use App\Models\Anggota;
use Illuminate\Http\Request;

class KasKeluarController extends Controller
{
    public function index()
    {
        try {
            $kasKeluar = KasKeluar::with('anggota')->orderBy('tanggal', 'desc')->get();

            $formattedKasKeluar = $kasKeluar->map(function ($item) {
                return [
                    'id' => $item->id,
                    'tanggal' => $item->tanggal->toDateString(),
                    'deskripsi' => $item->deskripsi,
                    'jumlah' => floatval($item->jumlah),
                    'anggota' => $item->anggota->name ?? 'N/A',
                ];
            });

            return response()->json([
                'data' => $formattedKasKeluar->values()->all(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch kas keluar data.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'tanggal' => 'required|date',
                'deskripsi' => 'required|string|max:255',
                'jumlah' => 'required|numeric|min:0',
                'anggota_id' => 'required|exists:anggotas,id',
            ]);

            $kasKeluar = KasKeluar::create([
                'tanggal' => $request->tanggal,
                'deskripsi' => $request->deskripsi,
                'jumlah' => $request->jumlah,
                'anggota_id' => $request->anggota_id,
            ]);

            $kasKeluar->load('anggota');

            return response()->json([
                'message' => 'Kas keluar berhasil ditambahkan',
                'data' => [
                    'id' => $kasKeluar->id,
                    'tanggal' => $kasKeluar->tanggal->toDateString(),
                    'deskripsi' => $kasKeluar->deskripsi,
                    'jumlah' => floatval($kasKeluar->jumlah),
                    'anggota' => $kasKeluar->anggota->name ?? 'N/A',
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create kas keluar data.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getAnggotaList()
    {
        try {
            $anggota = Anggota::select('id', 'name')->orderBy('name', 'asc')->get();

            return response()->json([
                'data' => $anggota,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch anggota data.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

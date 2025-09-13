<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SimpleKasController extends Controller
{
    public function storeKasMasuk(Request $request)
    {
        try {
            $request->validate([
                'tanggal' => 'required|date',
                'deskripsi' => 'required|string|max:255',
                'jumlah' => 'required|numeric|min:0',
            ]);

            $id = DB::table('kas_masuks')->insertGetId([
                'tanggal' => $request->tanggal,
                'deskripsi' => $request->deskripsi,
                'jumlah' => $request->jumlah,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'message' => 'Kas masuk berhasil ditambahkan',
                'data' => [
                    'id' => $id,
                    'tanggal' => $request->tanggal,
                    'deskripsi' => $request->deskripsi,
                    'jumlah' => floatval($request->jumlah),
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create kas masuk data.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function storeKasKeluar(Request $request)
    {
        try {
            $request->validate([
                'tanggal' => 'required|date',
                'deskripsi' => 'required|string|max:255',
                'jumlah' => 'required|numeric|min:0',
                'anggota_id' => 'required|integer|exists:anggotas,id',
            ]);

            $id = DB::table('kas_keluars')->insertGetId([
                'tanggal' => $request->tanggal,
                'deskripsi' => $request->deskripsi,
                'jumlah' => $request->jumlah,
                'anggota_id' => $request->anggota_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'message' => 'Kas keluar berhasil ditambahkan',
                'data' => [
                    'id' => $id,
                    'tanggal' => $request->tanggal,
                    'deskripsi' => $request->deskripsi,
                    'jumlah' => floatval($request->jumlah),
                    'anggota_id' => $request->anggota_id,
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
            $anggotas = DB::table('anggotas')
                ->select('id', 'nama')
                ->orderBy('nama')
                ->get();

            return response()->json([
                'data' => $anggotas->map(function ($anggota) {
                    return [
                        'id' => $anggota->id,
                        'nama' => $anggota->nama,
                    ];
                })->toArray(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch anggota list.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

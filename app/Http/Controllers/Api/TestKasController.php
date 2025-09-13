<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class TestKasController
{
    public function testConnection()
    {
        return response()->json([
            'message' => 'Connection successful',
            'timestamp' => now(),
        ]);
    }
    
    public function storeKasMasuk(Request $request)
    {
        try {
            // Validate input
            if (!$request->has(['tanggal', 'deskripsi', 'jumlah'])) {
                return response()->json([
                    'error' => 'Missing required fields',
                    'required' => ['tanggal', 'deskripsi', 'jumlah']
                ], 400);
            }

            // Insert directly using DB query
            $id = DB::table('kas_masuks')->insertGetId([
                'tanggal' => $request->tanggal,
                'deskripsi' => $request->deskripsi,
                'jumlah' => floatval($request->jumlah),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kas masuk berhasil ditambahkan',
                'data' => [
                    'id' => $id,
                    'tanggal' => $request->tanggal,
                    'deskripsi' => $request->deskripsi,
                    'jumlah' => floatval($request->jumlah),
                ],
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to insert kas masuk',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
    public function storeKasKeluar(Request $request)
    {
        try {
            // Validate input
            if (!$request->has(['tanggal', 'deskripsi', 'jumlah', 'anggota_id'])) {
                return response()->json([
                    'error' => 'Missing required fields',
                    'required' => ['tanggal', 'deskripsi', 'jumlau', 'anggota_id']
                ], 400);
            }

            // Insert directly using DB query
            $id = DB::table('kas_keluars')->insertGetId([
                'tanggal' => $request->tanggal,
                'deskripsi' => $request->deskripsi,
                'jumlah' => floatval($request->jumlah),
                'anggota_id' => intval($request->anggota_id),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kas keluar berhasil ditambahkan',
                'data' => [
                    'id' => $id,
                    'tanggal' => $request->tanggal,
                    'deskripsi' => $request->deskripsi,
                    'jumlah' => floatval($request->jumlah),
                    'anggota_id' => intval($request->anggota_id),
                ],
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to insert kas keluar',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

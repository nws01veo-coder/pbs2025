<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KasMasuk;
use Illuminate\Http\Request;

class KasMasukController extends Controller
{
    public function index()
    {
        try {
            $kasMasuk = KasMasuk::orderBy('tanggal', 'desc')->get();

            $formattedKasMasuk = $kasMasuk->map(function ($item) {
                return [
                    'id' => $item->id,
                    'tanggal' => $item->tanggal->toDateString(),
                    'deskripsi' => $item->deskripsi,
                    'jumlah' => floatval($item->jumlah),
                ];
            });

            return response()->json([
                'data' => $formattedKasMasuk->values()->all(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch kas masuk data.',
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
            ]);

            $kasMasuk = KasMasuk::create([
                'tanggal' => $request->tanggal,
                'deskripsi' => $request->deskripsi,
                'jumlah' => $request->jumlah,
            ]);

            return response()->json([
                'message' => 'Kas masuk berhasil ditambahkan',
                'data' => [
                    'id' => $kasMasuk->id,
                    'tanggal' => $kasMasuk->tanggal->toDateString(),
                    'deskripsi' => $kasMasuk->deskripsi,
                    'jumlah' => floatval($kasMasuk->jumlah),
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create kas masuk data.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

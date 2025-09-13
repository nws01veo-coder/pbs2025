<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\KasMasuk;
use App\Models\KasKeluar;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $dashboardData = [
                'total_anggota' => (string)Anggota::count(),
                'anggota_ikut_arisan' => (string)Anggota::where('aktif_arisan', true)->count(),
                'total_kas_masuk' => (string)KasMasuk::sum('jumlah'),
                'total_kas_keluar' => (string)KasKeluar::sum('jumlah'),
                'total_kas_saat_ini' => (string)(KasMasuk::sum('jumlah') - KasKeluar::sum('jumlah')),
            ];

            return response()->json([
                'data' => $dashboardData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch dashboard data.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
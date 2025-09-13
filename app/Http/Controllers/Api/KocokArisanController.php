<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class KocokArisanController extends Controller
{
    /**
     * Display a listing of kocok arisan results.
     */
    public function index(Request $request)
    {
        try {
            $query = DB::table('kocok_arisan as ka')
                ->leftJoin('anggotas as a', 'ka.anggota_id', '=', 'a.id')
                ->select(
                    'ka.*',
                    'a.nama as nama_anggota'
                )
                ->orderBy('ka.tahun', 'desc')
                ->orderBy('ka.periode', 'desc');

            // Filter by year if provided
            if ($request->has('tahun')) {
                $query->where('ka.tahun', $request->tahun);
            }

            $kocokArisan = $query->get();

            return response()->json([
                'success' => true,
                'message' => 'Data kocok arisan berhasil diambil',
                'data' => $kocokArisan
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kocok arisan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Perform kocok arisan for a specific month and year.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'bulan' => 'required|string',
                'tahun' => 'required|integer|min:2020|max:2030',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid',
                    'errors' => $validator->errors()
                ], 422);
            }

            $bulan = $request->bulan;
            $tahun = $request->tahun;

            // Check if kocok arisan already exists for this month and year
            $existingKocok = DB::table('kocok_arisan')
                ->where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->first();

            if ($existingKocok) {
                return response()->json([
                    'success' => false,
                    'message' => "Kocok arisan untuk $bulan $tahun sudah pernah dilakukan"
                ], 422);
            }

            // Get all members who are eligible for arisan (not based on active status, but on arisan participation)
            $activeMembers = DB::table('anggotas')
                ->where('status', 'aktif')
                ->where('aktif_arisan', true)
                ->pluck('id')
                ->toArray();

            // Debug: Log eligible members
            Log::info('Eligible members for arisan:', $activeMembers);
            
            // Also get names for debugging
            $eligibleMembersWithNames = DB::table('anggotas')
                ->where('status', 'aktif')
                ->where('aktif_arisan', true)
                ->select('id', 'name', 'aktif_arisan')
                ->get();
            
            Log::info('Eligible members with names:', $eligibleMembersWithNames->toArray());

            if (empty($activeMembers)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada anggota yang diikutkan dalam kocok arisan. Silakan aktifkan toggle "Ikut Kocok Arisan" untuk beberapa anggota.'
                ], 422);
            }

            // Get members who haven't won this year
            $currentYearWinners = DB::table('kocok_arisan')
                ->where('tahun', $tahun)
                ->pluck('anggota_id')
                ->toArray();

            $availableMembers = array_diff($activeMembers, $currentYearWinners);

            // If all members have won this year, use all active members
            if (empty($availableMembers)) {
                $availableMembers = $activeMembers;
            }

            // Randomly select a winner
            $winnerId = $availableMembers[array_rand($availableMembers)];

            // Get current period number
            $currentPeriode = DB::table('kocok_arisan')
                ->where('tahun', $tahun)
                ->max('periode') ?? 0;
            $newPeriode = $currentPeriode + 1;

            // Insert the result
            $kocokId = DB::table('kocok_arisan')->insertGetId([
                'periode' => $newPeriode,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'anggota_id' => $winnerId,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Get the result with member name
            $result = DB::table('kocok_arisan as ka')
                ->leftJoin('anggotas as a', 'ka.anggota_id', '=', 'a.id')
                ->select(
                    'ka.*',
                    'a.nama as nama_anggota'
                )
                ->where('ka.id', $kocokId)
                ->first();

            return response()->json([
                'success' => true,
                'message' => 'Kocok arisan berhasil dilakukan',
                'data' => $result
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan kocok arisan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics for kocok arisan.
     */
    public function statistik()
    {
        try {
            $totalPeriode = DB::table('kocok_arisan')->count();
            $currentYear = date('Y');
            $currentYearCount = DB::table('kocok_arisan')
                ->where('tahun', $currentYear)
                ->count();
            
            $yearlyStats = DB::table('kocok_arisan')
                ->select('tahun', DB::raw('count(*) as total'))
                ->groupBy('tahun')
                ->orderBy('tahun', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Statistik kocok arisan berhasil diambil',
                'data' => [
                    'total_periode' => $totalPeriode,
                    'current_year_count' => $currentYearCount,
                    'yearly_stats' => $yearlyStats
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik: ' . $e->getMessage()
            ], 500);
        }
    }
}

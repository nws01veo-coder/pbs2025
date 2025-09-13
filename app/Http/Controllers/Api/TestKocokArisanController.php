<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TestKocokArisanController extends Controller
{
    public function testConnection()
    {
        try {
            // Test database connection
            $count = DB::table('anggotas')->count();
            
            return response()->json([
                'success' => true,
                'message' => 'Connection successful',
                'anggota_count' => $count
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        try {
            // Get kocok arisan data with anggota names and photos
            $kocokArisan = DB::table('kocok_arisan as ka')
                ->leftJoin('anggotas as a', 'ka.anggota_id', '=', 'a.id')
                ->select(
                    'ka.id',
                    'ka.periode', 
                    'ka.bulan',
                    'ka.tahun',
                    'ka.anggota_id',
                    'ka.status',
                    'ka.created_at',
                    'ka.updated_at',
                    'a.name as nama_anggota',
                    'a.alias as alias_anggota',
                    'a.image as foto_anggota'
                )
                ->orderBy('ka.created_at', 'desc')
                ->get();
            
            // Format foto URL seperti di AnggotaController
            $formattedKocokArisan = $kocokArisan->map(function ($item) {
                $fotoUrl = null;
                if ($item->foto_anggota) {
                    $filename = basename($item->foto_anggota);
                    $fotoUrl = url('api/anggota-images/' . $filename);
                }
                
                return [
                    'id' => $item->id,
                    'periode' => $item->periode,
                    'bulan' => $item->bulan,
                    'tahun' => $item->tahun,
                    'anggota_id' => $item->anggota_id,
                    'status' => $item->status,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'nama_anggota' => $item->nama_anggota,
                    'alias_anggota' => $item->alias_anggota,
                    'foto_anggota' => $fotoUrl,
                ];
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Data kocok arisan berhasil diambil',
                'data' => $formattedKocokArisan
            ], 200);
        } catch (\Exception $e) {
            Log::error('Get Kocok Arisan Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $bulan = $request->input('bulan', 'Januari');
            $tahun = intval($request->input('tahun', date('Y')));
            
            // Add random suffix to bulan to avoid unique constraint
            $bulanUnique = $bulan . '_' . time() . '_' . rand(1000, 9999);
            
            // Get anggota that are active in arisan
            $activeArisanMembers = DB::table('anggotas')
                ->where('status', 'aktif')
                ->where('aktif_arisan', true)
                ->pluck('id')
                ->toArray();
                
            Log::info('TestKocokArisan - Active arisan members:', $activeArisanMembers);
            
            if (empty($activeArisanMembers)) {
                Log::warning('TestKocokArisan - No eligible members found');
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada anggota yang diikutkan dalam arisan. Silakan aktifkan toggle "Ikut Kocok Arisan" untuk beberapa anggota.'
                ], 422);
            }
            
            // Get members who have already won this year
            $currentYearWinners = DB::table('kocok_arisan')
                ->where('tahun', $tahun)
                ->pluck('anggota_id')
                ->toArray();
                
            Log::info('TestKocokArisan - Current year winners:', $currentYearWinners);
            
            // Get available members (haven't won this year)
            $availableMembers = array_diff($activeArisanMembers, $currentYearWinners);
            
            Log::info('TestKocokArisan - Available members (not won yet):', $availableMembers);
            
            // If no available members, all have won
            if (empty($availableMembers)) {
                Log::info('TestKocokArisan - All members have won this year');
                return response()->json([
                    'success' => false,
                    'message' => 'Semua anggota sudah mendapat arisan untuk tahun ' . $tahun . '. Tidak ada anggota yang tersisa untuk dikocok.',
                    'all_won' => true
                ], 422);
            }
            
            // Randomly select from available members
            $selectedMemberId = $availableMembers[array_rand($availableMembers)];
            
            $anggota = DB::table('anggotas')
                ->where('id', $selectedMemberId)
                ->first();
            
            Log::info('TestKocokArisan - Selected anggota:', ['id' => $anggota->id, 'name' => $anggota->name]);
            
            // Get current periode - make it more unique
            $maxPeriode = DB::table('kocok_arisan')
                ->where('tahun', $tahun)
                ->max('periode') ?? 0;
            $newPeriode = $maxPeriode + 1;
            
            // Insert kocok result
            $id = DB::table('kocok_arisan')->insertGetId([
                'periode' => $newPeriode,
                'bulan' => $bulanUnique,
                'tahun' => $tahun,
                'anggota_id' => $anggota->id,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Get result with anggota name
            $result = DB::table('kocok_arisan as ka')
                ->leftJoin('anggotas as a', 'ka.anggota_id', '=', 'a.id')
                ->select(
                    'ka.id',
                    'ka.periode', 
                    'ka.bulan',
                    'ka.tahun',
                    'ka.anggota_id',
                    'ka.status',
                    'ka.created_at',
                    'ka.updated_at',
                    'a.name as nama_anggota'
                )
                ->where('ka.id', $id)
                ->first();
                
            // Clean the bulan field for response
            if ($result) {
                $result->bulan = $bulan; // Return original bulan name
                $result->periode = $newPeriode; // Ensure correct periode
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Kocok arisan berhasil',
                'data' => $result
            ], 201);
        } catch (\Exception $e) {
            Log::error('Kocok Arisan Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'debug' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Check if kocok arisan exists
            $kocokArisan = DB::table('kocok_arisan')->where('id', $id)->first();
            
            if (!$kocokArisan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data kocok arisan tidak ditemukan'
                ], 404);
            }
            
            // Delete the record
            $deleted = DB::table('kocok_arisan')->where('id', $id)->delete();
            
            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pemenang kocok arisan berhasil dihapus'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus pemenang'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Delete Kocok Arisan Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getRecentWinners()
    {
        try {
            // Get recent 3 winners
            $recentWinners = DB::table('kocok_arisan as ka')
                ->leftJoin('anggotas as a', 'ka.anggota_id', '=', 'a.id')
                ->select(
                    'ka.id',
                    'ka.periode', 
                    'ka.bulan',
                    'ka.tahun',
                    'ka.anggota_id',
                    'ka.created_at as tanggal_kocok',
                    'a.name as nama'
                )
                ->orderBy('ka.created_at', 'desc')
                ->limit(3)
                ->get();
            
            // Format the data for Flutter
            $formattedWinners = $recentWinners->map(function ($winner) {
                // Clean bulan name (remove unique suffix)
                $bulan = $winner->bulan;
                if (strpos($bulan, '_') !== false) {
                    $bulan = substr($bulan, 0, strpos($bulan, '_'));
                }
                
                return [
                    'nama' => $winner->nama ?? 'Tidak Diketahui',
                    'bulan' => $bulan,
                    'tahun' => (string) $winner->tahun,
                    'tanggal_kocok' => $winner->tanggal_kocok,
                ];
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Data pemenang terbaru berhasil diambil',
                'data' => $formattedWinners
            ], 200);
        } catch (\Exception $e) {
            Log::error('Get Recent Winners Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}

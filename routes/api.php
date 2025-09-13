<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\JadwalController;
use App\Http\Controllers\Api\AnggotaController;
use App\Http\Controllers\Api\KasMasukController;
use App\Http\Controllers\Api\KasKeluarController;
use App\Http\Controllers\Api\SimpleKasController;
use App\Http\Controllers\Api\TestKasController;
use App\Http\Controllers\Api\ResourceController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\NavigationController;
use App\Http\Controllers\Api\JadwalArisanController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\KocokArisanController;
use App\Http\Controllers\Api\SimpleKocokArisanController;
use App\Http\Controllers\Api\TestKocokArisanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SimpleNotificationController;
use App\Http\Controllers\FirebaseNotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route untuk mengambil data menu navigasi dari Filament
// Public routes
Route::get('/anggota-images/{filename}', [AnggotaController::class, 'getImage']);
Route::get('/gallery-images/{filename}', [GalleryController::class, 'getImage']);

// Temporary public dashboard route for testing (remove auth requirement)
Route::get('/dashboard-test', [DashboardController::class, 'index']);

// Temporary public anggota route for testing (remove auth requirement)
Route::get('/anggota-test', [AnggotaController::class, 'index']);

// Temporary public jadwal route for testing (remove auth requirement)
Route::get('/jadwal-test', [JadwalController::class, 'index']);

// Temporary public kas routes for testing (remove auth requirement)
Route::get('/kas-masuk-test', [KasMasukController::class, 'index']);
Route::post('/kas-masuk-test', [KasMasukController::class, 'store']);
Route::get('/kas-keluar-test', [KasKeluarController::class, 'index']);
Route::post('/kas-keluar-test', [KasKeluarController::class, 'store']);
Route::get('/kas-keluar-test/anggota', [KasKeluarController::class, 'getAnggotaList']);

// Simple kas routes without Filament dependency
Route::post('/simple-kas-masuk', [SimpleKasController::class, 'storeKasMasuk']);
Route::post('/simple-kas-keluar', [SimpleKasController::class, 'storeKasKeluar']);
Route::get('/simple-anggota-list', [SimpleKasController::class, 'getAnggotaList']);

// Test kas routes - minimal dependency
Route::get('/test-connection', [TestKasController::class, 'testConnection']);
Route::post('/test-kas-masuk', [TestKasController::class, 'storeKasMasuk']);
Route::post('/test-kas-keluar', [TestKasController::class, 'storeKasKeluar']);

// Temporary public gallery route for testing (remove auth requirement)
Route::get('/gallery-test', [GalleryController::class, 'index']);

// Temporary public kocok arisan routes for testing (remove auth requirement)
Route::get('/test-kocok-arisan-connection', [TestKocokArisanController::class, 'testConnection']);
Route::get('/test-kocok-arisan', [TestKocokArisanController::class, 'index']);
Route::post('/test-kocok-arisan', [TestKocokArisanController::class, 'store']);
Route::delete('/test-kocok-arisan/{id}', [TestKocokArisanController::class, 'destroy']);
Route::get('/kocok-arisan/recent-winners', [TestKocokArisanController::class, 'getRecentWinners']);

// Even simpler test endpoint
Route::post('/simple-test-kocok', function() {
    return response()->json([
        'success' => true,
        'message' => 'Simple test berhasil',
        'data' => [
            'id' => 1,
            'periode' => 1,
            'bulan' => 'Test Bulan',
            'tahun' => 2025,
            'anggota_id' => 1,
            'nama_anggota' => 'Test User',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now()
        ]
    ], 201);
});

// Protected routes that require authentication and appropriate permissions
Route::middleware(['auth:sanctum'])->group(function () {
    // Navigation and dashboard
    Route::get('/navigation', [NavigationController::class, 'index'])
        ->middleware('permission:access_app')
        ->name('api.navigation');
    
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('permission:access_app');
    
    // Anggota routes (remove middleware temporarily for debugging)
    Route::get('/anggota', [AnggotaController::class, 'index']);
    Route::get('/anggota/{id}', [AnggotaController::class, 'show']);
    Route::put('/anggota/{id}/update-image', [AnggotaController::class, 'updateImage']);
    Route::delete('/anggota/{id}', [AnggotaController::class, 'destroy'])
        ->middleware('permission:manage_anggota');
    
    // Jadwal routes
    Route::get('/jadwal', [JadwalController::class, 'index'])
        ->middleware('permission:view_jadwal');
    
    // Kas routes
    Route::get('/kas-masuk', [KasMasukController::class, 'index'])
        ->middleware('permission:view_kas');
    Route::post('/kas-masuk', [KasMasukController::class, 'store'])
        ->middleware('permission:manage_kas');
    
    Route::get('/kas-keluar', [KasKeluarController::class, 'index'])
        ->middleware('permission:view_kas');
    Route::post('/kas-keluar', [KasKeluarController::class, 'store'])
        ->middleware('permission:manage_kas');
    Route::get('/kas-keluar/anggota', [KasKeluarController::class, 'getAnggotaList'])
        ->middleware('permission:view_kas');
    
    // Gallery routes
    Route::get('/gallery', [GalleryController::class, 'index'])
        ->middleware('permission:view_gallery');
    
    Route::post('/gallery', [GalleryController::class, 'store'])
        ->middleware('permission:manage_gallery');
    
    Route::get('/gallery/{id}', [GalleryController::class, 'show'])
        ->middleware('permission:view_gallery');
    
    Route::put('/gallery/{id}', [GalleryController::class, 'update'])
        ->middleware('permission:manage_gallery');
    
    Route::delete('/gallery/{id}', [GalleryController::class, 'destroy'])
        ->middleware('permission:manage_gallery');
    
    // Kocok Arisan routes
    Route::get('/kocok-arisan', [KocokArisanController::class, 'index']);
    Route::post('/kocok-arisan', [KocokArisanController::class, 'store']);
    Route::get('/kocok-arisan/statistik', [KocokArisanController::class, 'statistik']);
});

// Route dinamis untuk mengambil data dari resource mana pun (misalnya, /api/resources/anggotas)
Route::get('/resources/{resource}', [ResourceController::class, 'index']);

// Test route - untuk memastikan API berfungsi
Route::get('/test', [App\Http\Controllers\Api\TestController::class, 'test']);

// Test login route with debug info
Route::post('/login-test', [App\Http\Controllers\Api\LoginTestController::class, 'login']);

// Auth routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/change-password', [AuthController::class, 'changePassword'])->middleware('auth:sanctum');
Route::get('/check-permissions', [AuthController::class, 'checkPermissions'])->middleware('auth:sanctum');
Route::get('/users', [AuthController::class, 'index']);

// Notification routes
Route::post('/notification/send', [NotificationController::class, 'sendNotification']);
Route::post('/notification/save-token', [NotificationController::class, 'saveToken']);
Route::post('/notification/remove-token', [NotificationController::class, 'removeToken']);

// Simple notification routes (fallback)
Route::post('/simple-notification/send', [SimpleNotificationController::class, 'sendLocalNotification']);
Route::get('/simple-notification/pending', [SimpleNotificationController::class, 'getPendingNotifications']);

// Firebase notification routes (new improved version)
Route::get('/firebase/test-connection', [FirebaseNotificationController::class, 'testConnection']);
Route::post('/firebase/test-notification', [FirebaseNotificationController::class, 'sendTestNotification']);
Route::post('/firebase/kas-notification', [FirebaseNotificationController::class, 'sendKasNotification']);

// Simple test ping tanpa middleware
Route::post('/simple-ping', function() {
    return response()->json([
        'success' => true,
        'message' => 'Ping received',
        'timestamp' => now(),
    ]);
});

// Test endpoint untuk cek anggota yang aktif arisan
Route::get('/test-anggota-aktif-arisan', function () {
    try {
        $allMembers = DB::table('anggotas')
            ->select('id', 'name', 'status', 'aktif_arisan')
            ->get();
            
        $activeArisanMembers = DB::table('anggotas')
            ->where('status', 'aktif')
            ->where('aktif_arisan', true)
            ->select('id', 'name', 'status', 'aktif_arisan')
            ->get();
            
        return response()->json([
            'success' => true,
            'all_members' => $allMembers,
            'active_arisan_members' => $activeArisanMembers,
            'count_all' => $allMembers->count(),
            'count_active_arisan' => $activeArisanMembers->count(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => true,
            'error' => $e->getMessage()
        ], 500);
    }
});

// Test endpoint untuk lihat data kocok arisan
Route::get('/test-kocok-arisan-data', function () {
    try {
        $kocokData = DB::table('kocok_arisan as ka')
            ->leftJoin('anggotas as a', 'ka.anggota_id', '=', 'a.id')
            ->select(
                'ka.*',
                'a.name as nama_anggota',
                'a.aktif_arisan'
            )
            ->orderBy('ka.created_at', 'desc')
            ->limit(10)
            ->get();
            
        return response()->json([
            'success' => true,
            'kocok_data' => $kocokData,
            'count' => $kocokData->count(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Google\Client as GoogleClient;

class FirebaseNotificationController extends Controller
{
    // Untuk sementara, gunakan Legacy FCM
    private $legacyServerKey = 'YOUR_LEGACY_SERVER_KEY_HERE'; // Ganti dengan Server Key dari Firebase Console
    private $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
    
    // Untuk Firebase Admin SDK (recommended approach)
    private $serviceAccountPath = null; // Path ke service account JSON file
    
    public function testConnection()
    {
        try {
            // Test sederhana untuk memastikan endpoint bisa diakses
            return response()->json([
                'success' => true,
                'message' => 'Firebase Notification Controller is working',
                'server_key_set' => !empty($this->legacyServerKey) && $this->legacyServerKey !== 'YOUR_LEGACY_SERVER_KEY_HERE',
                'fcm_url' => $this->fcmUrl,
                'tokens_available' => $this->getTokenCount()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function sendTestNotification(Request $request)
    {
        try {
            if ($this->legacyServerKey === 'YOUR_LEGACY_SERVER_KEY_HERE') {
                return response()->json([
                    'success' => false,
                    'message' => 'Please update the legacy server key in FirebaseNotificationController.php'
                ], 400);
            }
            
            $tokens = $this->getAllActiveTokens();
            
            if (empty($tokens)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No FCM tokens found. Please ensure devices are registered.'
                ], 400);
            }
            
            $payload = [
                'registration_ids' => $tokens,
                'notification' => [
                    'title' => 'Test Notification',
                    'body' => 'This is a test notification from Firebase',
                    'sound' => 'default',
                    'badge' => 1
                ],
                'data' => [
                    'type' => 'test',
                    'timestamp' => now()->toISOString()
                ]
            ];
            
            Log::info('Sending test notification', [
                'tokens_count' => count($tokens),
                'server_key_preview' => substr($this->legacyServerKey, 0, 20) . '...'
            ]);
            
            $response = Http::withHeaders([
                'Authorization' => 'key=' . $this->legacyServerKey,
                'Content-Type' => 'application/json',
            ])->post($this->fcmUrl, $payload);
            
            Log::info('FCM Response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            if ($response->successful()) {
                $responseData = $response->json();
                return response()->json([
                    'success' => true,
                    'message' => 'Test notification sent successfully',
                    'recipients' => count($tokens),
                    'fcm_response' => $responseData
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send notification',
                    'error' => $response->body(),
                    'status' => $response->status()
                ], 500);
            }
            
        } catch (\Exception $e) {
            Log::error('Test notification error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function sendKasNotification(Request $request)
    {
        $request->validate([
            'type' => 'required|in:kas_masuk,kas_keluar',
            'jumlah' => 'required|numeric',
            'keterangan' => 'required|string'
        ]);
        
        $title = $request->type === 'kas_masuk' ? 'Kas Masuk 💰' : 'Kas Keluar 💸';
        $body = ($request->type === 'kas_masuk' ? 'Pemasukan' : 'Pengeluaran') . 
                ' Rp ' . number_format($request->jumlah, 0, ',', '.') . 
                ' - ' . $request->keterangan;
        
        return $this->sendNotification($title, $body, $request->type, [
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan
        ]);
    }
    
    private function sendNotification($title, $body, $type, $data = [])
    {
        try {
            if ($this->legacyServerKey === 'YOUR_LEGACY_SERVER_KEY_HERE') {
                return response()->json([
                    'success' => false,
                    'message' => 'Please update the legacy server key'
                ], 400);
            }
            
            $tokens = $this->getAllActiveTokens();
            
            if (empty($tokens)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No FCM tokens found'
                ], 400);
            }
            
            $payload = [
                'registration_ids' => $tokens,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                    'sound' => 'default',
                    'badge' => 1
                ],
                'data' => array_merge([
                    'type' => $type,
                    'timestamp' => now()->toISOString()
                ], $data)
            ];
            
            $response = Http::withHeaders([
                'Authorization' => 'key=' . $this->legacyServerKey,
                'Content-Type' => 'application/json',
            ])->post($this->fcmUrl, $payload);
            
            if ($response->successful()) {
                Log::info('Notification sent successfully', [
                    'type' => $type,
                    'recipients' => count($tokens)
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Notification sent successfully',
                    'recipients' => count($tokens)
                ]);
            } else {
                Log::error('Failed to send notification', [
                    'error' => $response->body(),
                    'status' => $response->status()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send notification',
                    'error' => $response->body()
                ], 500);
            }
            
        } catch (\Exception $e) {
            Log::error('Notification error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    private function getAllActiveTokens()
    {
        return DB::table('fcm_tokens')
            ->where('updated_at', '>=', now()->subDays(30))
            ->pluck('token')
            ->toArray();
    }
    
    private function getTokenCount()
    {
        return DB::table('fcm_tokens')
            ->where('updated_at', '>=', now()->subDays(30))
            ->count();
    }
}
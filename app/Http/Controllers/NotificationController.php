<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    // Firebase HTTP v1 API configuration
    private $projectId = 'pbs'; // Ganti dengan Project ID Firebase Anda
    private $fcmUrl; // Will be set in constructor
    
    public function __construct()
    {
        $this->fcmUrl = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";
    }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'type' => 'required|string',
            'data' => 'array',
            'tokens' => 'array', // FCM tokens dari devices
        ]);

        $tokens = $request->tokens ?? $this->getAllActiveTokens();

        if (empty($tokens)) {
            Log::info('No FCM tokens found for notification');
            return response()->json(['message' => 'No active tokens found'], 200);
        }

        $successCount = 0;
        $failureCount = 0;
        $errors = [];

        // Firebase HTTP v1 API requires sending to one token at a time
        foreach ($tokens as $token) {
            try {
                $payload = [
                    'message' => [
                        'token' => $token,
                        'notification' => [
                            'title' => $request->title,
                            'body' => $request->body,
                        ],
                        'data' => array_merge([
                            'type' => $request->type,
                            'timestamp' => now()->toISOString(),
                        ], $request->data ?? []),
                        'android' => [
                            'priority' => 'high',
                            'notification' => [
                                'sound' => 'default',
                                'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
                            ]
                        ]
                    ]
                ];

                Log::info('Sending notification to FCM v1', [
                    'title' => $request->title,
                    'body' => $request->body,
                    'type' => $request->type,
                    'token_preview' => substr($token, 0, 20) . '...',
                    'fcm_url' => $this->fcmUrl
                ]);

                // For now, use a simple approach without OAuth2 (will need service account later)
                // This is a temporary solution to test basic functionality
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post($this->fcmUrl, $payload);

                Log::info('FCM v1 Response received', [
                    'status' => $response->status(),
                    'body_preview' => substr($response->body(), 0, 500),
                ]);

                if ($response->successful()) {
                    $successCount++;
                } else {
                    $failureCount++;
                    $errors[] = [
                        'token' => substr($token, 0, 20) . '...',
                        'error' => $response->body()
                    ];
                }
            } catch (\Exception $e) {
                $failureCount++;
                $errors[] = [
                    'token' => substr($token, 0, 20) . '...',
                    'error' => $e->getMessage()
                ];
            }
        }

        Log::info('Notification batch completed', [
            'type' => $request->type,
            'total_tokens' => count($tokens),
            'success_count' => $successCount,
            'failure_count' => $failureCount
        ]);

        return response()->json([
            'success' => $successCount > 0,
            'message' => "Notification sent to {$successCount} devices, {$failureCount} failed",
            'recipients' => count($tokens),
            'success_count' => $successCount,
            'failure_count' => $failureCount,
            'errors' => $errors
        ]);
    }

    public function sendAnggotaBaruNotification($anggota)
    {
        return $this->sendNotification(new Request([
            'title' => 'Anggota Baru Bergabung! 🎉',
            'body' => $anggota->nama . ' telah bergabung sebagai anggota baru',
            'type' => 'anggota_baru',
            'data' => [
                'id' => $anggota->id,
                'nama' => $anggota->nama,
            ],
        ]));
    }

    public function sendKasMasukNotification($kas)
    {
        // Handle both objects and arrays
        $id = is_array($kas) ? ($kas['id'] ?? null) : $kas->id;
        $jumlah = is_array($kas) ? ($kas['amount'] ?? $kas['jumlah'] ?? 0) : $kas->jumlah;
        $keterangan = is_array($kas) ? ($kas['description'] ?? $kas['keterangan'] ?? $kas['deskripsi'] ?? '') : ($kas->keterangan ?? $kas->deskripsi ?? '');
        
        return $this->sendNotification(new Request([
            'title' => 'Kas Masuk 💰',
            'body' => 'Pemasukan Rp ' . number_format($jumlah, 0, ',', '.') . ' - ' . $keterangan,
            'type' => 'kas_masuk',
            'data' => [
                'id' => $id,
                'jumlah' => $jumlah,
                'keterangan' => $keterangan,
            ],
        ]));
    }

    public function sendKasKeluarNotification($kas)
    {
        // Handle both objects and arrays
        $id = is_array($kas) ? ($kas['id'] ?? null) : $kas->id;
        $jumlah = is_array($kas) ? ($kas['amount'] ?? $kas['jumlah'] ?? 0) : $kas->jumlah;
        $keterangan = is_array($kas) ? ($kas['description'] ?? $kas['keterangan'] ?? $kas['deskripsi'] ?? '') : ($kas->keterangan ?? $kas->deskripsi ?? '');
        
        return $this->sendNotification(new Request([
            'title' => 'Kas Keluar 💸',
            'body' => 'Pengeluaran Rp ' . number_format($jumlah, 0, ',', '.') . ' - ' . $keterangan,
            'type' => 'kas_keluar',
            'data' => [
                'id' => $id,
                'jumlah' => $jumlah,
                'keterangan' => $keterangan,
            ],
        ]));
    }

    public function sendJadwalUpdateNotification($jadwal)
    {
        return $this->sendNotification(new Request([
            'title' => 'Jadwal Terbaru 📅',
            'body' => $jadwal->nama_kegiatan . ' - ' . $jadwal->tanggal->format('d/m/Y'),
            'type' => 'jadwal_update',
            'data' => [
                'id' => $jadwal->id,
                'kegiatan' => $jadwal->nama_kegiatan,
                'tanggal' => $jadwal->tanggal->format('Y-m-d'),
            ],
        ]));
    }

    public function sendGaleriFotoNotification($jumlahFoto, $galeriId)
    {
        return $this->sendNotification(new Request([
            'title' => 'Foto Baru di Galeri 📸',
            'body' => $jumlahFoto . ' foto baru telah ditambahkan ke galeri',
            'type' => 'galeri_foto',
            'data' => [
                'id' => $galeriId,
                'jumlah' => $jumlahFoto,
            ],
        ]));
    }

    public function sendGaleriVideoNotification($jumlahVideo, $galeriId)
    {
        return $this->sendNotification(new Request([
            'title' => 'Video Baru di Galeri 🎥',
            'body' => $jumlahVideo . ' video baru telah ditambahkan ke galeri',
            'type' => 'galeri_video',
            'data' => [
                'id' => $galeriId,
                'jumlah' => $jumlahVideo,
            ],
        ]));
    }

    public function saveToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'user_id' => 'nullable|integer',
        ]);

        // Simpan atau update FCM token
        $existingToken = DB::table('fcm_tokens')
            ->where('token', $request->token)
            ->first();

        if ($existingToken) {
            DB::table('fcm_tokens')
                ->where('token', $request->token)
                ->update([
                    'user_id' => $request->user_id,
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('fcm_tokens')->insert([
                'token' => $request->token,
                'user_id' => $request->user_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json(['message' => 'Token saved successfully']);
    }

    public function removeToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        DB::table('fcm_tokens')
            ->where('token', $request->token)
            ->delete();

        return response()->json(['message' => 'Token removed successfully']);
    }

    private function getAllActiveTokens()
    {
        return DB::table('fcm_tokens')
            ->where('updated_at', '>=', now()->subDays(30)) // Hanya token yang aktif dalam 30 hari terakhir
            ->pluck('token')
            ->toArray();
    }
}

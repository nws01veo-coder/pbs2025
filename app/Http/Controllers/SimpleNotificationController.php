<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SimpleNotificationController extends Controller
{
    /**
     * Send notification using local notification system (without FCM)
     * This is a fallback when Firebase is not available
     */
    public function sendLocalNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'type' => 'required|string',
            'data' => 'array'
        ]);

        try {
            // Store notification in database for polling
            $notification = [
                'title' => $request->title,
                'body' => $request->body,
                'type' => $request->type,
                'data' => json_encode($request->data ?? []),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            DB::table('notifications')->insert($notification);

            Log::info('Local notification stored', [
                'title' => $request->title,
                'body' => $request->body,
                'type' => $request->type
            ]);

            // Broadcast event for real-time updates (if using websockets)
            // broadcast(new NotificationEvent($notification));

            return response()->json([
                'success' => true,
                'message' => 'Local notification sent successfully',
                'notification' => $notification
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send local notification: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pending notifications for a user
     */
    public function getPendingNotifications(Request $request)
    {
        try {
            $notifications = DB::table('notifications')
                ->where('created_at', '>', now()->subMinutes(5)) // Last 5 minutes
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'title' => $notification->title,
                        'body' => $notification->body,
                        'type' => $notification->type,
                        'data' => json_decode($notification->data, true),
                        'created_at' => $notification->created_at
                    ];
                });

            return response()->json([
                'success' => true,
                'notifications' => $notifications
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get notifications: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send kas masuk notification using local system
     */
    public function sendKasMasukNotificationLocal($kas)
    {
        $id = is_array($kas) ? ($kas['id'] ?? null) : $kas->id;
        $jumlah = is_array($kas) ? ($kas['amount'] ?? $kas['jumlah'] ?? 0) : $kas->jumlah;
        $keterangan = is_array($kas) ? ($kas['description'] ?? $kas['keterangan'] ?? $kas['deskripsi'] ?? '') : ($kas->keterangan ?? $kas->deskripsi ?? '');
        
        return $this->sendLocalNotification(new Request([
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

    /**
     * Send kas keluar notification using local system
     */
    public function sendKasKeluarNotificationLocal($kas)
    {
        $id = is_array($kas) ? ($kas['id'] ?? null) : $kas->id;
        $jumlah = is_array($kas) ? ($kas['amount'] ?? $kas['jumlah'] ?? 0) : $kas->jumlah;
        $keterangan = is_array($kas) ? ($kas['description'] ?? $kas['keterangan'] ?? $kas['deskripsi'] ?? '') : ($kas->keterangan ?? $kas->deskripsi ?? '');
        
        return $this->sendLocalNotification(new Request([
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
}
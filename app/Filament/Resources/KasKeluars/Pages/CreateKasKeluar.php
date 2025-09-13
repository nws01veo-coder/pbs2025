<?php

namespace App\Filament\Resources\KasKeluars\Pages;

use App\Filament\Resources\KasKeluars\KasKeluarResource;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SimpleNotificationController;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateKasKeluar extends CreateRecord
{
    protected static string $resource = KasKeluarResource::class;

    protected function getCreateAnotherButton(): bool
    {
        return false;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        // Trigger notification setelah kas keluar dibuat
        $kasKeluar = $this->record;
        
        try {
            // Try Firebase FCM first
            $notificationController = new NotificationController();
            $response = $notificationController->sendKasKeluarNotification($kasKeluar);
            
            // If Firebase fails, use simple local notification as fallback
            if (!$response || (is_object($response) && method_exists($response, 'getData') && !$response->getData()->success)) {
                Log::info('Firebase notification failed, using local notification fallback');
                $simpleNotificationController = new SimpleNotificationController();
                $simpleNotificationController->sendKasKeluarNotificationLocal($kasKeluar);
            }
            
            // Log untuk debugging
            Log::info('Notification triggered for KasKeluar', [
                'id' => $kasKeluar->id,
                'jumlah' => $kasKeluar->jumlah,
                'keterangan' => $kasKeluar->deskripsi ?? $kasKeluar->keterangan ?? 'N/A'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send kas keluar notification', [
                'error' => $e->getMessage(),
                'kas_keluar_id' => $kasKeluar->id
            ]);
            
            // Final fallback - simple local notification
            try {
                $simpleNotificationController = new SimpleNotificationController();
                $simpleNotificationController->sendKasKeluarNotificationLocal($kasKeluar);
                Log::info('Local notification fallback executed successfully');
            } catch (\Exception $fallbackError) {
                Log::error('Even local notification fallback failed', [
                    'error' => $fallbackError->getMessage()
                ]);
            }
        }
    }
}

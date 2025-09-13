<?php

namespace App\Filament\Resources\KasMasuks\Pages;

use App\Filament\Resources\KasMasuks\KasMasukResource;
use App\Http\Controllers\NotificationController;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class CreateKasMasuk extends CreateRecord
{
    protected static string $resource = KasMasukResource::class;

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
        // Trigger notification setelah kas masuk dibuat
        $kasMasuk = $this->record;
        
        try {
            $notificationController = new NotificationController();
            $notificationController->sendKasMasukNotification($kasMasuk);
            
            // Log untuk debugging
            Log::info('Notification triggered for KasMasuk', [
                'id' => $kasMasuk->id,
                'jumlah' => $kasMasuk->jumlah,
                'keterangan' => $kasMasuk->keterangan
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send kas masuk notification', [
                'error' => $e->getMessage(),
                'kas_masuk_id' => $kasMasuk->id
            ]);
        }
    }
}

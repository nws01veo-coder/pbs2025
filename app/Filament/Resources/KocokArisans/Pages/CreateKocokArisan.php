<?php

namespace App\Filament\Resources\KocokArisans\Pages;

use App\Filament\Resources\KocokArisanResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateKocokArisan extends CreateRecord
{
    protected static string $resource = KocokArisanResource::class;
    
    public function getTitle(): string
    {
        return 'Tambah Penerima Arisan';
    }
    
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Penerima Berhasil Ditambahkan')
            ->body('Data penerima kocok arisan telah berhasil disimpan.');
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

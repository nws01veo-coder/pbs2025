<?php

namespace App\Filament\Resources\KocokArisanResource\Pages;

use App\Filament\Resources\KocokArisanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditKocokArisan extends EditRecord
{
    protected static string $resource = KocokArisanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Hapus Pemenang')
                ->modalDescription('Apakah Anda yakin ingin menghapus data pemenang ini?')
                ->modalSubmitActionLabel('Ya, Hapus'),
        ];
    }
    
    public function getTitle(): string
    {
        return 'Edit Pemenang Kocok Arisan';
    }
    
    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Pemenang Berhasil Diperbarui')
            ->body('Data pemenang kocok arisan telah berhasil diperbarui.');
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

<?php

namespace App\Filament\Resources\KocokArisans\Pages;

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
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Hapus Penerima Arisan')
                ->modalDescription('Apakah Anda yakin ingin menghapus data penerima ini?')
                ->modalSubmitActionLabel('Ya, Hapus'),
        ];
    }
    
    public function getTitle(): string
    {
        return 'Edit Penerima Arisan';
    }
    
    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Penerima Berhasil Diperbarui')
            ->body('Data penerima arisan telah berhasil diperbarui.');
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

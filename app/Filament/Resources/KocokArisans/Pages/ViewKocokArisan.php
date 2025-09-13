<?php

namespace App\Filament\Resources\KocokArisans\Pages;

use App\Filament\Resources\KocokArisanResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKocokArisan extends ViewRecord
{
    protected static string $resource = KocokArisanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Hapus Penerima Arisan')
                ->modalDescription('Apakah Anda yakin ingin menghapus data penerima ini?')
                ->modalSubmitActionLabel('Ya, Hapus'),
        ];
    }
    
    public function getTitle(): string
    {
        return 'Detail Penerima Kocok Arisan';
    }
}

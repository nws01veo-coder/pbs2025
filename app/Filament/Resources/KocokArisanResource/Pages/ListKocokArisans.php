<?php

namespace App\Filament\Resources\KocokArisanResource\Pages;

use App\Filament\Resources\KocokArisanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKocokArisans extends ListRecords
{
    protected static string $resource = KocokArisanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Pemenang')
                ->icon('heroicon-o-plus'),
        ];
    }
    
    public function getTitle(): string
    {
        return 'Daftar Pemenang Kocok Arisan';
    }
    
    protected function getHeaderWidgets(): array
    {
        return [
            // Add any widgets here if needed
        ];
    }
}

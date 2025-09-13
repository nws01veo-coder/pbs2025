<?php

namespace App\Filament\Resources\KocokArisans\Pages;

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
                ->label('Tambah Penerima')
                ->icon('heroicon-o-plus'),
        ];
    }
    
    public function getTitle(): string
    {
        return 'Daftar Penerima Arisan';
    }
}

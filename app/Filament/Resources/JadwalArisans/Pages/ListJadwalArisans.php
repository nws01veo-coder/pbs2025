<?php

namespace App\Filament\Resources\JadwalArisans\Pages;

use App\Filament\Resources\JadwalArisans\JadwalArisanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJadwalArisans extends ListRecords
{
    protected static string $resource = JadwalArisanResource::class;
    protected static ?string $createButtonLabel = 'Buat Jadwal';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

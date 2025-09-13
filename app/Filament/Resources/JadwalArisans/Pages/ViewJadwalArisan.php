<?php

namespace App\Filament\Resources\JadwalArisans\Pages;

use App\Filament\Resources\JadwalArisans\JadwalArisanResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewJadwalArisan extends ViewRecord
{
    protected static string $resource = JadwalArisanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

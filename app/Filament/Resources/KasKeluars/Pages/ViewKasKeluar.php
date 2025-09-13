<?php

namespace App\Filament\Resources\KasKeluars\Pages;

use App\Filament\Resources\KasKeluars\KasKeluarResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKasKeluar extends ViewRecord
{
    protected static string $resource = KasKeluarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

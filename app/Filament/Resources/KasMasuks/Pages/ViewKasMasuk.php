<?php

namespace App\Filament\Resources\KasMasuks\Pages;

use App\Filament\Resources\KasMasuks\KasMasukResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKasMasuk extends ViewRecord
{
    protected static string $resource = KasMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

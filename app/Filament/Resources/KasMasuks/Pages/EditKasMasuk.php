<?php

namespace App\Filament\Resources\KasMasuks\Pages;

use App\Filament\Resources\KasMasuks\KasMasukResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKasMasuk extends EditRecord
{
    protected static string $resource = KasMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

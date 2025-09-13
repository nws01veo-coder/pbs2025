<?php

namespace App\Filament\Resources\KasKeluars\Pages;

use App\Filament\Resources\KasKeluars\KasKeluarResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKasKeluar extends EditRecord
{
    protected static string $resource = KasKeluarResource::class;

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

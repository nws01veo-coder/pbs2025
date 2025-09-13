<?php

namespace App\Filament\Resources\JadwalArisans\Pages;

use App\Filament\Resources\JadwalArisans\JadwalArisanResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditJadwalArisan extends EditRecord
{
    protected static string $resource = JadwalArisanResource::class;

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

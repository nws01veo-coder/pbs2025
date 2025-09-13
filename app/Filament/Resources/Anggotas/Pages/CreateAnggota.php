<?php

namespace App\Filament\Resources\Anggotas\Pages;

use App\Filament\Resources\Anggotas\AnggotaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAnggota extends CreateRecord
{
    protected static string $resource = AnggotaResource::class;

    protected function getCreateAnotherButton(): bool
    {
        return false;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

<?php

namespace App\Filament\Resources\JadwalArisans\Pages;

use App\Filament\Resources\JadwalArisans\JadwalArisanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJadwalArisan extends CreateRecord
{
    protected static string $resource = JadwalArisanResource::class;

    protected function getCreateAnotherButton(): bool
    {
        return false;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

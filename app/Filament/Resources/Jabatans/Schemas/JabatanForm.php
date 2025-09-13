<?php

namespace App\Filament\Resources\Jabatans\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class JabatanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}

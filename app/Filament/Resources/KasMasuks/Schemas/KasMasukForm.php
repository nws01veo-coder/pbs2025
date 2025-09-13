<?php

namespace App\Filament\Resources\KasMasuks\Schemas;

use Filament\Forms\Get;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;

use function Laravel\Prompts\textarea;

class KasMasukForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('tanggal')
                    ->required()
                    ->default(now()),
                TextInput::make('deskripsi')
                    ->required(),
                TextInput::make('jumlah')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
            ])->columns(2);
    }
}

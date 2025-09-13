<?php

namespace App\Filament\Resources\KasKeluars\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;

class KasKeluarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('tanggal')
                        ->required()
                        ->default(now()),

                    TextInput::make('deskripsi')
                        ->required()
                        ->autofocus()
                        ->placeholder('Contoh: Biaya Kopi & Snack Rapat'),

                    TextInput::make('jumlah')
                        ->numeric()
                        ->prefix('Rp')
                        ->required(),

                    Select::make('anggota_id')
                        ->relationship('anggota', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->placeholder('Yang menerima Kas'),
            ])->columns(2);
    }
}

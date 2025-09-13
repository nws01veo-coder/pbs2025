<?php

namespace App\Filament\Resources\JadwalArisans\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Utilities\Get;

class JadwalArisanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Event Information')
                    ->schema([
                        DatePicker::make('tanggal')
                            ->required()
                            ->default(now()),

                        Radio::make('acara')
                            ->options([
                                'arisan' => 'Arisan',
                                'adat' => 'Adat',
                                'lainnya' => 'Lainnya',
                            ])
                            ->default('arisan')
                            ->live()
                            ->required(),

                        Select::make('anggota_id')
                            ->relationship('anggota', 'name')
                            ->label('Anggota')
                            ->visible(fn(Get $get) => in_array($get('acara'), ['arisan', 'adat']))
                            ->required(fn(Get $get) => in_array($get('acara'), ['arisan', 'adat']))
                            ->searchable()
                            ->preload(),

                        TextInput::make('deskripsi')
                            ->placeholder('Contoh: Arisan Bulanan, Rapat Anggota, dll.')
                            ->maxLength(255),
                    ])
                    ->columns(1),

                Section::make('Location Information')
                    ->schema([
                        Select::make('lokasi_id')
                            ->relationship('lokasi', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('alamat_rumah')
                            ->placeholder('Contoh: Jl. Diponegoro No. 123'),

                        TextInput::make('titik_alamat_rumah')
                            ->url()
                            ->label('Link Google Maps')
                            ->placeholder('Contoh: https://maps.app.goo.gl/abcdefg'),
                    ])
                    ->columns(1),
            ]);
    }
}

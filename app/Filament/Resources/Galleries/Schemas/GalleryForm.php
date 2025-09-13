<?php

namespace App\Filament\Resources\Galleries\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Card;
use Filament\Schemas\Components\Section;

class GalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Media Information')
                    ->schema([
                        TextInput::make('nama')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('link')
                            ->url()
                            ->label('Link Image/Video')
                            ->required()
                            ->maxLength(255),
                        Radio::make('jenis')
                            ->label('Jenis')
                            ->options([
                                'foto' => 'Foto',
                                'video' => 'Video',
                            ])
                            ->required(),
                    ]),
                Section::make('Additional Details')
                    ->schema([
                        Textarea::make('deskripsi')
                            ->required(),
                        Select::make('lokasi_id')
                            ->label('Lokasi')
                            ->relationship('lokasi', 'name')
                            ->required(),
                    ]),
            ]);
    }
}

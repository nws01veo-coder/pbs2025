<?php

namespace App\Filament\Resources\Anggotas\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Section;

class AnggotaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Personal')
                    ->schema([
                        ImageEntry::make('image')
                            ->circular()
                            ->label('Foto'),
                        TextEntry::make('name')
                            ->label('Nama Lengkap'),
                        TextEntry::make('alias')
                            ->label('Alias/Nama Panggilan')
                            ->placeholder('-'),
                        TextEntry::make('jenis_kelamin')
                            ->label('Jenis Kelamin'),
                    ])->columns(2),
                Section::make('Detail Anggota')
                    ->schema([
                        TextEntry::make('status'),
                        TextEntry::make('lokasi.name')
                            ->label('Lokasi'),
                        TextEntry::make('jabatan.name')
                            ->label('Jabatan'),
                        TextEntry::make('aktif_arisan')
                            ->label('Ikut Kocok Arisan')
                            ->formatStateUsing(fn ($state) => $state ? 'Ya' : 'Tidak'),
                    ])->columns(2),
                Section::make('Kontak Informasi')
                    ->schema([
                        TextEntry::make('no_telp')
                            ->label('No Telp'),
                        TextEntry::make('alamat')
                            ->label('Alamat'),
                    ])->columns(2),
            ]);
    }
}

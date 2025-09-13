<?php

namespace App\Filament\Resources\Anggotas\Schemas;

use App\JenisKelamin;
use App\StatusAnggota;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;

class AnggotaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Personal')
                    ->schema([
                        FileUpload::make('image')
                            ->image()
                            ->directory('anggota-images')
                            ->visibility('public'),
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('alias')
                            ->label('Alias/Nama Panggilan')
                            ->placeholder('Contoh: Mama Evelyn, Papa John, dll')
                            ->helperText('Nama panggilan yang biasa digunakan (opsional)')
                            ->maxLength(255),
                        Select::make('jenis_kelamin')
                            ->options(JenisKelamin::class)
                            ->required(),
                    ]),
                Section::make('Detil Anggota')
                    ->schema([
                        Select::make('status')
                            ->options(StatusAnggota::class)
                            ->required(),
                        Select::make('lokasi_id')
                            ->relationship('lokasi', 'name')
                            ->required(),
                        Select::make('jabatan_id')
                            ->relationship('jabatan', 'name')
                            ->required(),
                        Toggle::make('aktif_arisan')
                            ->label('Ikut Kocok Arisan')
                            ->helperText('Centang untuk menyertakan anggota ini dalam kocok arisan. Berguna untuk membedakan suami/istri dalam satu keluarga.')
                            ->default(true),
                    ]),
                Section::make('Kontak Informasi')
                    ->schema([
                        TextInput::make('no_telp')
                            ->label('No Telp')
                            ->maxLength(20),
                        TextInput::make('alamat')
                            ->label('Alamat')
                            ->maxLength(255),
                    ]),
            ]);
    }
}

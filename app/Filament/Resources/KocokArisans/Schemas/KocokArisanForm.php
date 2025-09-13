<?php

namespace App\Filament\Resources\KocokArisans\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KocokArisanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('periode')
                    ->label('Periode')
                    ->numeric()
                    ->required()
                    ->placeholder('Contoh: 1, 2, 3, dst.')
                    ->helperText('Urutan periode arisan'),
                    
                Select::make('tahun')
                    ->label('Tahun')
                    ->options(function () {
                        $currentYear = date('Y');
                        $years = [];
                        for ($i = $currentYear - 2; $i <= $currentYear + 2; $i++) {
                            $years[$i] = $i;
                        }
                        return $years;
                    })
                    ->required()
                    ->default(date('Y')),
                    
                Select::make('bulan')
                    ->label('Bulan')
                    ->options([
                        'Januari' => 'Januari',
                        'Februari' => 'Februari',
                        'Maret' => 'Maret',
                        'April' => 'April',
                        'Mei' => 'Mei',
                        'Juni' => 'Juni',
                        'Juli' => 'Juli',
                        'Agustus' => 'Agustus',
                        'September' => 'September',
                        'Oktober' => 'Oktober',
                        'November' => 'November',
                        'Desember' => 'Desember',
                    ])
                    ->required()
                    ->default('September'),
                    
                Select::make('anggota_id')
                    ->label('Pemenang')
                    ->relationship('anggota', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->placeholder('Pilih anggota pemenang'),
                    
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Aktif',
                        'completed' => 'Selesai',
                        'pending' => 'Menunggu',
                    ])
                    ->default('active')
                    ->required(),
            ]);
    }
}

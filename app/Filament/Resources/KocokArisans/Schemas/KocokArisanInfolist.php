<?php

namespace App\Filament\Resources\KocokArisans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class KocokArisanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('periode')
                    ->label('Periode')
                    ->badge()
                    ->color('primary'),
                    
                TextEntry::make('bulan')
                    ->label('Bulan'),
                    
                TextEntry::make('tahun')
                    ->label('Tahun'),
                    
                TextEntry::make('anggota.name')
                    ->label('Nama Pemenang')
                    ->weight('bold')
                    ->color('success'),
                    
                TextEntry::make('anggota.jabatan')
                    ->label('Jabatan')
                    ->badge(),
                    
                TextEntry::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'completed' => 'primary', 
                        'pending' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Aktif',
                        'completed' => 'Selesai',
                        'pending' => 'Menunggu',
                        default => $state,
                    }),
                    
                TextEntry::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d F Y, H:i:s'),
                    
                TextEntry::make('updated_at')
                    ->label('Terakhir Diupdate')
                    ->dateTime('d F Y, H:i:s'),
            ]);
    }
}

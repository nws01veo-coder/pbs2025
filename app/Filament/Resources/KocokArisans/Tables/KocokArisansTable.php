<?php

namespace App\Filament\Resources\KocokArisans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class KocokArisansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('periode')
                    ->label('Periode')
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                    
                TextColumn::make('bulan')
                    ->label('Bulan')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function ($state) {
                        // Jika sudah berupa nama bulan Indonesia, return apa adanya
                        $bulanIndonesia = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                                         'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                        
                        if (in_array($state, $bulanIndonesia)) {
                            return $state;
                        }
                        
                        // Jika berupa angka, konversi ke nama bulan
                        if (is_numeric($state)) {
                            $bulanMap = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ];
                            return $bulanMap[(int)$state] ?? $state;
                        }
                        
                        // Jika berupa string dengan angka di depan (seperti "9_123123213"), ambil angka pertama
                        if (preg_match('/^(\d+)/', $state, $matches)) {
                            $monthNumber = (int)$matches[1];
                            $bulanMap = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ];
                            return $bulanMap[$monthNumber] ?? $state;
                        }
                        
                        return $state;
                    }),
                    
                TextColumn::make('tahun')
                    ->label('Tahun')
                    ->sortable(),
                    
                TextColumn::make('anggota.name')
                    ->label('Pemenang')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),
                    
                TextColumn::make('status')
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
                    
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('tahun')
                    ->label('Filter Tahun')
                    ->options(function () {
                        $currentYear = date('Y');
                        $years = [];
                        for ($i = $currentYear - 2; $i <= $currentYear + 2; $i++) {
                            $years[$i] = $i;
                        }
                        return $years;
                    }),
                    
                SelectFilter::make('bulan')
                    ->label('Filter Bulan')
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
                    ]),
                    
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'active' => 'Aktif',
                        'completed' => 'Selesai',
                        'pending' => 'Menunggu',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50]);
    }
}

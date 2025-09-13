<?php

namespace App\Filament\Resources\JadwalArisans\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;

class JadwalArisansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),

                TextColumn::make('anggota.name')
                    ->label('Anggota')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('acara')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'arisan' => 'success',
                        'adat' => 'warning',
                        'lainnya' => 'primary',
                    }),

                TextColumn::make('deskripsi')
                    ->label('Keterangan')
                    ->searchable(),

                TextColumn::make('lokasi.name')
                    ->label('Lokasi')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('titik_alamat_rumah')
                    ->label('Link Maps')
                    ->url(fn($record): ?string => $record->titik_alamat_rumah)
                    ->openUrlInNewTab()
                    ->limit(20),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

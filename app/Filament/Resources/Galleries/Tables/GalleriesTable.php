<?php

namespace App\Filament\Resources\Galleries\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;

class GalleriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->sortable()->searchable(),
                TextColumn::make('link')->label('Link Image/Video')->sortable()->url(fn($record): string => $record->link)->openUrlInNewTab()->limit(20),
                TextColumn::make('jenis')->label('Jenis')->sortable(),
                TextColumn::make('deskripsi')->limit(50),
                TextColumn::make('lokasi.name')->label('Lokasi')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}

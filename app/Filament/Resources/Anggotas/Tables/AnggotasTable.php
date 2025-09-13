<?php

namespace App\Filament\Resources\Anggotas\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;

class AnggotasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->circular()->label('Foto'),
                TextColumn::make('name')->searchable()->label('Nama'),
                TextColumn::make('alias')->searchable()->label('Alias')->placeholder('-'),
                TextColumn::make('jabatan.name')->searchable(),
                TextColumn::make('lokasi.name')->searchable(),
                TextColumn::make('jenis_kelamin'),
                TextColumn::make('no_telp')->label('No Telp'),
                // TextColumn::make('alamat')->label('Alamat'),
                TextColumn::make('status'),
                ToggleColumn::make('aktif_arisan')
                    ->label('Munculkan')
                    ->updateStateUsing(function ($record, $state) {
                        $record->update(['aktif_arisan' => $state]);
                        return $state;
                    }),

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

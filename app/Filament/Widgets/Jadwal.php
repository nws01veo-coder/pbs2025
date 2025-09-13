<?php

namespace App\Filament\Widgets;

use Filament\Tables\Table;
use App\Models\JadwalArisan;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseTableWidget;

class Jadwal extends BaseTableWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Jadwal Terbaru';
    // Mengatur urutan widget di dashboard.
    // Jika Anda ingin widget ini di posisi kedua, gunakan angka 2.
    protected static ?int $sort = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => JadwalArisan::query())
            ->columns([
                TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                
                TextColumn::make('acara')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'arisan' => 'success',
                        'adat' => 'warning',
                        'lainnya' => 'primary',
                    }),
                
                TextColumn::make('anggota.name')
                    ->label('Anggota Arisan')
                    // ->searchable()
                    ->sortable(),

                TextColumn::make('lokasi.name')
                    ->label('Lokasi')
                    // ->searchable()
                    ->sortable(),
                
                TextColumn::make('deskripsi')
                    ->limit(30),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
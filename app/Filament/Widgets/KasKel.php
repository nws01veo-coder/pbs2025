<?php

namespace App\Filament\Widgets;

use App\Models\KasKeluar;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseTableWidget;

class KasKel extends BaseTableWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Pengeluaran Terbaru';
    // Mengatur urutan widget di dashboard. 
    protected static ?int $sort = 6;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => KasKeluar::query())
            ->columns([
                TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),

                TextColumn::make('anggota.name')
                    ->label('Nama Pengeluaran')
                    // ->searchable()
                    ->sortable(),

                TextColumn::make('deskripsi')
                    ->limit(50),
                
                TextColumn::make('jumlah')
                    ->prefix('Rp ') // Otomatis memformat menjadi Rupiah
                    ->sortable(),
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
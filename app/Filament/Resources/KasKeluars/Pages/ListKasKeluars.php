<?php

namespace App\Filament\Resources\KasKeluars\Pages;

use Filament\Actions\Action;
use App\Exports\KasKeluarExport;
use App\Filament\Resources\KasKeluars\KasKeluarResource;
use Filament\Actions\CreateAction;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Resources\Pages\ListRecords;

class ListKasKeluars extends ListRecords
{
    protected static string $resource = KasKeluarResource::class;
    protected static ?string $createButtonLabel = 'Tambah Kas Keluar';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->createAnother(false)
                ->label('Tambah Kas Keluar'),
            Action::make('exportPdf')
                ->label('Export PDF')
                ->action(function () {
                    $kasKeluars = $this->getTableQuery()->with('anggota')->get();
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('filament.exports.kas_keluars', compact('kasKeluars'));
                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'kas_keluars.pdf'
                    );
                }),
            Action::make('exportExcel')
                ->label('Export Excel')
                ->action(function () {
                    return Excel::download(new KasKeluarExport, 'kas_keluars.xlsx');
                }),
        ];
    }
}

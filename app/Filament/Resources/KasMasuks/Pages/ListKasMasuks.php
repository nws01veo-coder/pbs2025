<?php

namespace App\Filament\Resources\KasMasuks\Pages;

use Filament\Actions\Action;
use App\Exports\KasMasukExport;
use App\Filament\Resources\KasMasuks\KasMasukResource;
use Filament\Actions\CreateAction;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Resources\Pages\ListRecords;

class ListKasMasuks extends ListRecords
{
    protected static string $resource = KasMasukResource::class;
    protected static ?string $createButtonLabel = 'Tambah Kas Masuk';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->createAnother(false)
                ->label('Tambah Kas Masuk'),
            Action::make('exportPdf')
                ->label('Export PDF')
                ->action(function () {
                    $kasMasuks = $this->getTableQuery()->get();
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('filament.exports.kas_masuks', compact('kasMasuks'));
                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'kas_masuks.pdf'
                    );
                }),
            Action::make('exportExcel')
                ->label('Export Excel')
                ->action(function () {
                    return Excel::download(new KasMasukExport, 'kas_masuks.xlsx');
                }),
        ];
    }
}

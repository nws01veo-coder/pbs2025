<?php

namespace App\Filament\Resources\Anggotas\Pages;

use Filament\Actions\Action;
use App\Exports\AnggotaExport;

use Filament\Actions\CreateAction;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Anggotas\AnggotaResource;

class ListAnggotas extends ListRecords
{
    protected static string $resource = AnggotaResource::class;
    protected static ?string $createButtonLabel = 'Tambah Anggota';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->createAnother(false)
                ->label('Tambah Anggota'),
            Action::make('exportPdf')
                ->label('Export PDF')
                ->action(function () {
                    $anggotas = $this->getTableQuery()->with('jabatan', 'lokasi')->get()->map(function ($anggota) {
                        $anggota->jenis_kelamin = $anggota->jenis_kelamin->value;
                        $anggota->status = $anggota->status->value;
                        return $anggota;
                    });
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('filament.exports.anggotas', compact('anggotas'));
                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'anggotas.pdf'
                    );
                }),
            Action::make('exportExcel')
                ->label('Export Excel')
                ->action(function () {
                    return Excel::download(new AnggotaExport, 'anggotas.xlsx');
                }),
        ];
    }


}

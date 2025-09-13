<?php

namespace App\Filament\Widgets;

use App\Models\Anggota;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use Filament\Support\Enums\IconPosition;

class TotalAnggotaWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    public ?string $filter = 'all';

    protected function getFilters(): ?array
    {
        return [
            'all' => 'Semua Status',
            'aktif' => 'Aktif Saja',
            'non_aktif' => 'Non Aktif Saja',
        ];
    }

    protected function getStats(): array
    {
        // Total anggota berdasarkan filter
        $totalAnggota = match($this->filter) {
            'aktif' => Anggota::where('status', 'Aktif')->count(),
            'non_aktif' => Anggota::where('status', '!=', 'Aktif')->count(),
            default => Anggota::count(),
        };

        // Anggota aktif arisan
        $anggotaAktifArisan = Anggota::where('aktif_arisan', true)->count();
        
        // Anggota baru bulan ini
        $anggotaBaruBulanIni = Anggota::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // Persentase aktif arisan
        $persentaseAktifArisan = $totalAnggota > 0 ? round(($anggotaAktifArisan / $totalAnggota) * 100, 1) : 0;

        return [
            Stat::make('Total Anggota', $totalAnggota)
                ->description($this->getFilterDescription())
                ->descriptionIcon('heroicon-m-users', IconPosition::Before)
                ->color('primary')
                ->chart([7, 12, 18, 25, 30, 35, $totalAnggota]),

            Stat::make('Aktif Arisan', $anggotaAktifArisan)
                ->description($persentaseAktifArisan . '% dari total anggota')
                ->descriptionIcon('heroicon-m-check-circle', IconPosition::Before)
                ->color('success')
                ->chart([3, 8, 12, 15, 20, 25, $anggotaAktifArisan]),

            Stat::make('Anggota Baru', $anggotaBaruBulanIni)
                ->description('Bergabung bulan ' . Carbon::now()->format('F Y'))
                ->descriptionIcon('heroicon-m-user-plus', IconPosition::Before)
                ->color('warning')
                ->chart([0, 1, 2, 1, 3, 2, $anggotaBaruBulanIni]),
        ];
    }

    private function getFilterDescription(): string
    {
        return match($this->filter) {
            'aktif' => 'Anggota dengan status aktif',
            'non_aktif' => 'Anggota dengan status non aktif',
            default => 'Seluruh anggota terdaftar',
        };
    }
}
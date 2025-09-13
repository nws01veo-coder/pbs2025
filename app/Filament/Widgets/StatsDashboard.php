<?php

namespace App\Filament\Widgets;

use App\Models\Anggota;
use App\Models\KasKeluar;
use App\Models\KasMasuk;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsDashboard extends BaseWidget
{
    protected static ?int $sort = 2;
    protected function getStats(): array
    {
        $totalAnggota = Anggota::count();
        $totalKasMasuk = KasMasuk::sum('jumlah');
        $totalKasKeluar = KasKeluar::sum('jumlah');
        $totalKas = $totalKasMasuk - $totalKasKeluar;

        return [
            Stat::make('Total Anggota', $totalAnggota)
                ->description('Jumlah seluruh anggota terdaftar'),
            Stat::make('Total Kas Masuk', 'Rp ' . number_format($totalKasMasuk, 0, ',', '.'))
                ->description('Jumlah seluruh pemasukan'),
            Stat::make('Total Kas Keluar', 'Rp ' . number_format($totalKasKeluar, 0, ',', '.'))
                ->description('Jumlah seluruh pengeluaran'),
            Stat::make('Total Kas Saat Ini', 'Rp ' . number_format($totalKas, 0, ',', '.'))
                ->description('Total kas')
                ->color($totalKas >= 0 ? 'success' : 'danger'),
        ];
    }
}

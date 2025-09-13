<?php

namespace App\Filament\Widgets;

use App\Models\KasMasuk;
use App\Models\KasKeluar;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class KasStatusWidget extends BaseWidget
{
    public ?string $filter = 'all_time';

    protected function getFilters(): ?array
    {
        return [
            'this_month' => 'Bulan Ini',
            'last_month' => 'Bulan Lalu',
            'this_year' => 'Tahun Ini',
            'last_year' => 'Tahun Lalu',
            'all_time' => 'Semua Waktu',
        ];
    }

    protected function getStats(): array
    {
        // Menentukan rentang tanggal berdasarkan filter
        $dateRange = $this->getDateRange();
        
        // Hitung total kas masuk berdasarkan filter
        $totalKasMasuk = KasMasuk::when($dateRange, function($query) use ($dateRange) {
            return $query->whereBetween('created_at', $dateRange);
        })->sum('jumlah');
        
        // Hitung total kas keluar berdasarkan filter
        $totalKasKeluar = KasKeluar::when($dateRange, function($query) use ($dateRange) {
            return $query->whereBetween('created_at', $dateRange);
        })->sum('jumlah');
        
        // Hitung saldo saat ini
        $saldoSaatIni = $totalKasMasuk - $totalKasKeluar;
        
        // Hitung persentase kas keluar
        $persentaseKasKeluar = $totalKasMasuk > 0 ? ($totalKasKeluar / $totalKasMasuk) * 100 : 0;
        
        // Tentukan warna berdasarkan persentase
        $warnaStatus = $persentaseKasKeluar >= 70 ? 'danger' : 'success';
        $iconStatus = $persentaseKasKeluar >= 70 ? 'heroicon-o-exclamation-triangle' : 'heroicon-o-check-circle';
        
        // Label periode
        $periodLabel = $this->getPeriodLabel();
        
        return [
            Stat::make("Kas Masuk {$periodLabel}", 'Rp ' . number_format($totalKasMasuk, 0, ',', '.'))
                ->description("Total pendapatan kas {$periodLabel}")
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color('success'),
                
            Stat::make("Kas Keluar {$periodLabel}", 'Rp ' . number_format($totalKasKeluar, 0, ',', '.'))
                ->description("Total pengeluaran kas {$periodLabel}")
                ->descriptionIcon('heroicon-o-arrow-trending-down')
                ->color('danger'),
                
            Stat::make("Saldo {$periodLabel}", 'Rp ' . number_format($saldoSaatIni, 0, ',', '.'))
                ->description("Saldo kas {$periodLabel}")
                ->descriptionIcon('heroicon-o-banknotes')
                ->color($saldoSaatIni >= 0 ? 'success' : 'danger'),
                
            Stat::make('Persentase Kas Keluar', round($persentaseKasKeluar, 1) . '%')
                ->description($persentaseKasKeluar >= 70 ? 'Status: Perhatian!' : 'Status: Aman')
                ->descriptionIcon($iconStatus)
                ->color($warnaStatus)
                ->chart([
                    $persentaseKasKeluar >= 70 ? 70 : $persentaseKasKeluar,
                    $persentaseKasKeluar,
                ]),
        ];
    }
    
    private function getDateRange(): ?array
    {
        return match ($this->filter) {
            'this_month' => [now()->startOfMonth(), now()->endOfMonth()],
            'last_month' => [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()],
            'this_year' => [now()->startOfYear(), now()->endOfYear()],
            'last_year' => [now()->subYear()->startOfYear(), now()->subYear()->endOfYear()],
            'all_time' => null,
            default => null,
        };
    }
    
    private function getPeriodLabel(): string
    {
        return match ($this->filter) {
            'this_month' => 'Bulan Ini',
            'last_month' => 'Bulan Lalu',
            'this_year' => 'Tahun Ini',
            'last_year' => 'Tahun Lalu',
            'all_time' => 'Semua Waktu',
            default => 'Semua Waktu',
        };
    }
}
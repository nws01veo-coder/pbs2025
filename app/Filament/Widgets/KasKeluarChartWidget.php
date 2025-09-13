<?php

namespace App\Filament\Widgets;

use App\Models\KasKeluar;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KasKeluarChartWidget extends ChartWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public ?string $filter = 'current_year';

    protected function getFilters(): ?array
    {
        return [
            'current_year' => 'Tahun Ini (' . date('Y') . ')',
            'last_year' => 'Tahun Lalu (' . (date('Y') - 1) . ')',
        ];
    }

    protected function getData(): array
    {
        $year = $this->filter === 'last_year' ? date('Y') - 1 : date('Y');
        
        // Ambil data pengeluaran per bulan untuk tahun yang dipilih
        $kasKeluarData = KasKeluar::select(
                DB::raw('MONTH(tanggal) as month'),
                DB::raw('SUM(jumlah) as total')
            )
            ->whereYear('tanggal', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        // Nama bulan dalam bahasa Indonesia
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $labels = [];
        $data = [];

        // Fill data untuk 12 bulan (Januari - Desember)
        for ($month = 1; $month <= 12; $month++) {
            $labels[] = $months[$month];
            $data[] = $kasKeluarData[$month] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pengeluaran (Rp)',
                    'data' => $data,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'borderColor' => 'rgb(239, 68, 68)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { 
                            return "Rp " + new Intl.NumberFormat("id-ID").format(value); 
                        }',
                    ],
                ],
            ],
            'interaction' => [
                'intersect' => false,
                'mode' => 'index',
            ],
            'elements' => [
                'point' => [
                    'radius' => 4,
                    'hoverRadius' => 6,
                ],
            ],
        ];
    }

    public function getHeading(): string
    {
        return 'Grafik Pengeluaran Kas Tahunan';
    }
}
<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\KasStatusWidget;
use App\Filament\Widgets\TotalAnggotaWidget;
use App\Filament\Widgets\KasKeluarChartWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $slug = '';
    
    public function getWidgets(): array
    {
        return [
            TotalAnggotaWidget::class,
            KasStatusWidget::class,
            KasKeluarChartWidget::class,
        ];
    }
}

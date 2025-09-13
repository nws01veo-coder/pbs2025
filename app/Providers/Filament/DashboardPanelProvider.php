<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Enums\Width;
use Filament\Support\Colors\Color;
use Filament\Pages\Dashboard;
use App\Filament\Widgets\StatsDashboard;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use App\Filament\Resources\Anggotas\AnggotaResource;
use App\Filament\Resources\Jabatans\JabatanResource;
use App\Filament\Resources\KasKeluars\KasKeluarResource;
use App\Filament\Resources\KasMasuks\KasMasukResource;
use App\Filament\Resources\Locations\LocationResource;
use App\Filament\Resources\Users\UserResource;
use App\Filament\Resources\Galleries\GalleryResource;

class DashboardPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('dashboard')
            ->path('dashboard')
            ->brandLogo(asset('images/logo.png'))
            ->brandLogoHeight('3rem')
            ->favicon(asset('images/logo.png'))
            ->login()
            ->maxContentWidth(Width::Full)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->resources([
                AnggotaResource::class,
                JabatanResource::class,
                KasKeluarResource::class,
                KasMasukResource::class,
                LocationResource::class,
                UserResource::class,
                GalleryResource::class,
            ])
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                StatsDashboard::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}

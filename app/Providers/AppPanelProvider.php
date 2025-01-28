<?php

declare(strict_types=1);

namespace App\Providers;

use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Tapp\FilamentWebhookClient\FilamentWebhookClientPlugin;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('app')
            ->path('app')
            ->topNavigation()
            ->topbar(true)
            ->spa()
            ->databaseTransactions()
//            ->darkMode(true)
            ->defaultThemeMode(ThemeMode::Dark)
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Yellow,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
                'secondary' => Color::Blue,
                'indigo' => Color::Indigo,
                'slate' => Color::Slate,
                'sky' => Color::Sky,
                'violet' => Color::Violet,
                'fuchsia' => Color::Fuchsia,
                'purple' => Color::Purple,
                'red' => Color::Red,
                'green' => Color::Green,
                'amber' => Color::Amber,
                'lime' => Color::Lime,
            ])
            ->font('Montserrat')
            ->brandLogo(asset('frontend/running/Logo_7.png'))
            ->darkModeBrandLogo(asset('frontend/running/Logo_8.png'))
            ->brandLogoHeight('2.5em')
            ->plugins([
                BreezyCore::make()
                    ->myProfile(
                        navigationGroup: 'Pengaturan',
                    ),
                FilamentWebhookClientPlugin::make(),
            ])
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            ->pages([
                //                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\\Filament\\App\\Widgets')
            ->widgets([
                //                Widgets\AccountWidget::class,
                //                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                //                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                //                Authenticate::class,
            ]);
    }
}

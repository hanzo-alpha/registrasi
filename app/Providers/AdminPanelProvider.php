<?php

declare(strict_types=1);

namespace App\Providers;

use App\Filament\Admin\Pages\Auth\Login;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Tapp\FilamentWebhookClient\FilamentWebhookClientPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->passwordReset()
            ->sidebarCollapsibleOnDesktop()
//            ->sidebarFullyCollapsibleOnDesktop()
            ->spa()
            ->databaseNotifications()
            ->databaseTransactions()
//            ->viteTheme('resources/css/filament/admin/theme.css')
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Lime,
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
                'yellow' => Color::Yellow,
            ])
            ->plugins([
                BreezyCore::make()
                    ->myProfile(
                        navigationGroup: 'Pengaturan',
                    ),
                FilamentWebhookClientPlugin::make(),
            ])
            ->resources([
                config('filament-logger.activity_resource'),
            ])
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->globalSearchFieldKeyBindingSuffix()
            ->font('Poppins')
            ->brandLogo(asset('frontend/running/Logo_7.png'))
            ->darkModeBrandLogo(asset('frontend/running/Logo_8.png'))
            ->brandLogoHeight('2.5em')
            ->favicon(asset('frontend/running/favicon.png'))
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
                //                Widgets\AccountWidget::class,
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

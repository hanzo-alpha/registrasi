<?php

declare(strict_types=1);

namespace App\Providers;

use App\Filament\Admin\Pages\Auth\Login;
use Croustibat\FilamentJobsMonitor\FilamentJobsMonitorPlugin;
use Devonab\FilamentEasyFooter\EasyFooterPlugin;
use Filafly\PhosphorIconReplacement;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Tapp\FilamentWebhookClient\FilamentWebhookClientPlugin;
use Vormkracht10\FilamentMails\FilamentMailsPlugin;

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
            ->spa()
            ->maxContentWidth(MaxWidth::Full)
            ->databaseNotifications()
            ->databaseTransactions()
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
                        navigationGroup: 'Settings',
                    ),
                FilamentWebhookClientPlugin::make(),
                FilamentJobsMonitorPlugin::make(),
                PhosphorIconReplacement::make(),
                EasyFooterPlugin::make()
                    ->withLoadTime('Halaman ini dimuat dalam ')
                    ->withGithub()
                    ->withSentence(config('app.brand') . ' - ' . config('app.event')),
                FilamentMailsPlugin::make(),
            ])
            ->resources([
                config('filament-logger.activity_resource'),
            ])
            ->navigationGroups([
                'Pendaftaran',
                'Master',
                'Settings',
                'Webhooks',
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
            ])
            ->viteTheme('resources/css/filament/admin/theme.css');
    }
}

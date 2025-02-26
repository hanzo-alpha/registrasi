<?php

declare(strict_types=1);

namespace App\Providers;

use App\Filament\App\Pages\Earlybird;
use App\Filament\App\Pages\Pendaftaran;
use App\Filament\App\Pages\RegistrasiPeserta;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Placeholder;
use Filament\Infolists\Components\Entry;
use Filament\Support\Components\Component;
use Filament\Support\Concerns\Configurable;
use Filament\Support\Facades\FilamentView;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Filament\View\PanelsRenderHook;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->configureCommands();
        $this->configureModels();
        $this->translatableComponents();
        $this->configureVite();
        $this->registerFilamentHook();
    }

    protected function translatableComponents(): void
    {
        foreach ([Field::class, BaseFilter::class, Placeholder::class, Column::class, Entry::class] as $component) {
            /* @var Configurable $component */
            $component::configureUsing(function (Component $translatable): void {
                /** @phpstan-ignore method.notFound */
                $translatable->translateLabel();
            });
        }
    }

    private function configureCommands(): void
    {
        DB::prohibitDestructiveCommands($this->app->isProduction());
    }

    private function registerFilamentHook(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_END,
            fn(array $scopes): View => view('payment-js', ['scopes' => $scopes]),
            scopes: [
                Pendaftaran::class,
                Earlybird::class,
                RegistrasiPeserta::class,
            ],
        );
    }

    private function configureModels(): void
    {
        Model::unguard();
        Model::shouldBeStrict($this->app->isProduction());
    }

    private function configureVite(): void
    {
        Vite::useWaterfallPrefetching(concurrency: 10);
        //        Vite::useAggressivePrefetching();
        //        Vite::usePrefetchStrategy('waterfall', ['concurrency' => 1]);
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Closure;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Outerweb\FilamentSettings\Filament\Pages\Settings as BaseSettings;

class Settings extends BaseSettings
{
    use HasPageShield;

    protected static ?string $navigationGroup = 'Settings';

    public static function getNavigationLabel(): string
    {
        return 'Portal Settings';
    }

    public function getTitle(): string
    {
        return 'Portal Pengaturan';
    }

    public function schema(): array|Closure
    {
        return [
            Section::make('General')
                ->description('Pengaturan umum aplikasi seperti brand name, logo, dan lainnya.')
                ->aside()
                ->schema([
                    TextInput::make('general.brand_name')
                        ->label('Nama Brand')
                        ->required(),
                ])
                ->inlineLabel(),

            Section::make('Seo')
                ->description('Pengaturan SEO untuk aplikasi seperti title, description, dan lainnya.')
                ->aside()
                ->schema([
                    TextInput::make('seo.title')
                        ->label('Judul')
                        ->required(),
                    TextInput::make('seo.description')
                        ->label('Penjelasan')
                        ->required(),
                ])->inlineLabel(),

            Section::make('Midtrans')
                ->description('Pengaturan Midtrans untuk aplikasi seperti merchant id, client key, dan lainnya.')
                ->aside()
                ->schema([
                    TextInput::make('midtrans.merchant_id')
                        ->label('Merchant ID')
                        ->required(),
                    TextInput::make('midtrans.production.client_key')
                        ->label('Production Client Key')
                        ->required(),
                    TextInput::make('midtrans.production.server_key')
                        ->label('Production Server Key')
                        ->required(),
                    TextInput::make('midtrans.sandbox.client_key')
                        ->label('Sandbox Client Key')
                        ->required(),
                    TextInput::make('midtrans.sandbox.server_key')
                        ->label('Sandbox Server Key')
                        ->required(),
                    Toggle::make('midtrans.is_production'),
                    Toggle::make('midtrans.is_3ds'),
                    Toggle::make('midtrans.is_sanitized'),

                ])->inlineLabel(),

            Section::make('Resend')
                ->description('Pengaturan Mail Resend seperti key dan signing secret.')
                ->aside()
                ->schema([
                    TextInput::make('resend.key')
                        ->label('Key')
                        ->required(),
                    TextInput::make('resend.signing_secret')
                        ->label('Signing Secret')
                        ->required(),

                ])->inlineLabel(),

            Section::make('Github')
                ->description('Pengaturan Github untuk aplikasi seperti repository, token, dan lainnya.')
                ->aside()
                ->schema([
                    TextInput::make('github.repository')
                        ->label('Repository')
                        ->required(),
                    TextInput::make('github.token')
                        ->label('Token')
                        ->required(),

                ])->inlineLabel(),

            Section::make('Cloudflare Turnstile')
                ->description('Pengaturan Github untuk aplikasi seperti repository, token, dan lainnya.')
                ->aside()
                ->schema([
                    TextInput::make('turnstile.site_key')
                        ->label('Site Key')
                        ->required(),
                    TextInput::make('turnstile.secret_key')
                        ->label('Secret Key')
                        ->required(),

                ])->inlineLabel(),

            Section::make('Sistem')
                ->description('Pengaturan umum aplikasi seperti brand name, logo, dan lainnya.')
                ->aside()
                ->schema([
                    Toggle::make('sistem.debugbar')
                        ->label('Aktifkan Debugbar'),
                ])
                ->inlineLabel(),
        ];
    }
}

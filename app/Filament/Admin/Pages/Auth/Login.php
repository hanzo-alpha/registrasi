<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages\Auth;

use Coderflex\FilamentTurnstile\Forms\Components\Turnstile;
use Filament\Pages\Auth\Login as BasePage;

class Login extends BasePage
{
    public function mount(): void
    {
        parent::mount();

        if (app()->isLocal()) {
            $this->form->fill([
                'email' => config('app.default_user.email'),
                'password' => config('app.default_user.password'),
                'remember' => true,
            ]);
        }
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
                        Turnstile::make('captcha')
                            ->hiddenLabel(true)
                            ->language('id')
                            ->theme('auto'),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    // if you want to reset the captcha in case of validation error
    protected function throwFailureValidationException(): never
    {
        $this->dispatch('reset-captcha');

        parent::throwFailureValidationException();
    }
}

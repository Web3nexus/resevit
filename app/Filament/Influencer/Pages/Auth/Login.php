<?php

namespace App\Filament\Influencer\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin implements HasSchemas
{
    use InteractsWithSchemas;

    protected function getSchemas(): array
    {
        return [
            'form',
        ];
    }

    public function getHeading(): string|Htmlable
    {
        return 'Welcome back, Partner!';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Log in to your influencer dashboard.';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Email address')
            ->placeholder('Your email')
            ->email()
            ->required()
            ->autocomplete()
            ->autofocus()
            ->prefixIcon('heroicon-o-envelope');
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Password')
            ->placeholder('Your password')
            ->password()
            ->revealable()
            ->required()
            ->prefixIcon('heroicon-o-lock-closed');
    }
}

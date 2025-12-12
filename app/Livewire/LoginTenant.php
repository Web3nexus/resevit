<?php

namespace App\Livewire;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LoginTenant extends Component implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('email')
                    ->placeholder('Email Address')
                    ->prefixIcon('heroicon-o-envelope')
                    ->extraInputAttributes(['class' => 'text-base'])
                    ->extraFieldWrapperAttributes(['class' => 'mb-3'])
                    ->email()
                    ->required()
                    ->autocomplete('email'),
                TextInput::make('password')
                    ->placeholder('Password')
                    ->prefixIcon('heroicon-o-lock-closed')
                    ->extraInputAttributes(['class' => 'text-base'])
                    ->extraFieldWrapperAttributes(['class' => 'mb-3'])
                    ->password()
                    ->revealable()
                    ->required()
                    ->autocomplete('current-password'),
            ])
            ->statePath('data');
    }

    public function authenticate()
    {
        $data = $this->form->getState();

        if (! Auth::attempt($data)) {
            $this->addError('data.email', __('auth.failed'));
            return;
        }

        session()->regenerate();

        // Redirect to dashboard logic or tenant selection if multiple
        // For now, redirect to the first tenant's dashboard if available, or stay here?
        // Actually, central login might be for managing profile or creating more restaurants.
        // Let's redirect to the first tenant found for convenience, or a specific central dashboard.
        
        $user = Auth::user();
        $tenant = $user->tenants->first();

        if ($tenant) {
            return redirect()->to(tenant_route($tenant->domains->first()->domain, 'filament.dashboard.pages.dashboard'));
        }

        return redirect('/'); // Fallback
    }

    public function render()
    {
        return view('livewire.login-tenant');
    }
}

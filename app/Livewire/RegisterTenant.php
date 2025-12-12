<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\TenantCreatorService;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;

use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class RegisterTenant extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->placeholder('Full Name')
                    ->prefixIcon('heroicon-o-user')
                    ->extraInputAttributes(['class' => 'text-base'])
                    ->extraFieldWrapperAttributes(['class' => 'mb-3'])
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->placeholder('Email Address')
                    ->prefixIcon('heroicon-o-envelope')
                    ->extraInputAttributes(['class' => 'text-base'])
                    ->extraFieldWrapperAttributes(['class' => 'mb-2'])
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique('users', 'email'), // Check global users
                TextInput::make('password')
                    ->placeholder('Password')
                    ->prefixIcon('heroicon-o-lock-closed')
                    ->extraInputAttributes(['class' => 'text-base'])
                    ->extraFieldWrapperAttributes(['class' => 'mb-3'])
                    ->password()
                    ->revealable()
                    ->required()
                    ->minLength(8),
                TextInput::make('restaurant_name')
                    ->placeholder('Restaurant Name')
                    ->prefixIcon('heroicon-o-building-storefront')
                    ->extraInputAttributes(['class' => 'text-base'])
                    ->extraFieldWrapperAttributes(['class' => 'mb-3'])
                    ->required()
                    ->maxLength(255),
            ])
            ->statePath('data');
    }

    public function create()
    {
        $data = $this->form->getState();

        // Auto-generate subdomain from restaurant name
        $subdomain = \Illuminate\Support\Str::slug($data['restaurant_name']);
        $subdomain = preg_replace('/[^a-z0-9]/', '', $subdomain); // Remove all non-alphanumeric
        
        // Ensure uniqueness
        $originalSubdomain = $subdomain;
        $counter = 1;
        while (\App\Models\Tenant::where('slug', $subdomain)->exists()) {
            $subdomain = $originalSubdomain . $counter;
            $counter++;
        }

        // 1. Create User (Landlord)
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // 2. Create Tenant with auto-generated subdomain
        $service = app(TenantCreatorService::class);
        $tenant = $service->createTenant($user, $data['restaurant_name'], $subdomain);

        Notification::make()
            ->title('Restaurant created successfully!')
            ->success()
            ->send();

        // 3. Redirect to Tenant Dashboard
        // e.g. http://pizza.resevit.test/dashboard/login
        return redirect()->to(tenant_route($tenant->domains->first()->domain, 'filament.dashboard.auth.login'));
    }

    public function render()
    {
        return view('livewire.register-tenant');
    }
}

<?php

namespace App\Filament\Dashboard\Pages;

use App\Models\Branch;
use App\Models\Tenant;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class Onboarding extends Page implements \Filament\Schemas\Contracts\HasSchemas
{
    use \Filament\Schemas\Concerns\InteractsWithSchemas;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-sparkles';

    protected string $view = 'filament.dashboard.pages.onboarding';

    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];

    public function mount(): void
    {
        if (tenant('onboarding_status') === 'active') {
            $this->redirect(route('filament.dashboard.pages.dashboard'));

            return;
        }

        $this->data = [];
    }

    protected function getSchemas(): array
    {
        return ['onboardingForm'];
    }

    public function onboardingForm(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Wizard::make([
                    Step::make('Welcome')
                        ->description('Let\'s set up your business')
                        ->schema([
                            \Filament\Forms\Components\Placeholder::make('welcome')
                                ->content('Welcome to Resevit! We need a few more details to get your restaurant running smoothly.'),
                        ]),
                    Step::make('First Branch')
                        ->description('Add your main location')
                        ->schema([
                            TextInput::make('branch_name')
                                ->label('Branch Name')
                                ->required()
                                ->placeholder('Main Branch')
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn($state, callable $set) => $set('branch_slug', Str::slug($state))),
                            TextInput::make('branch_slug')
                                ->label('Branch URL Slug')
                                ->required()
                                ->unique(Branch::class, 'slug'),
                            TextInput::make('branch_address')
                                ->label('Address')
                                ->required(),
                            TextInput::make('branch_phone')
                                ->label('Phone Number'),
                            TextInput::make('branch_email')
                                ->label('Branch Email')
                                ->email(),
                        ]),
                    Step::make('Initial Settings')
                        ->description('Configure your preferences')
                        ->schema([
                            Toggle::make('enable_reservations')
                                ->label('Enable Online Reservations')
                                ->default(true),
                            Toggle::make('enable_food_ordering')
                                ->label('Enable Online Food Ordering')
                                ->default(true),
                        ]),
                ])
                    ->submitAction(
                        \Filament\Actions\Action::make('submit')
                            ->label('Complete Setup')
                            ->color('primary')
                            ->size('lg')
                            ->submit('submit')
                    )
                    ->nextAction(
                        fn(\Filament\Actions\Action $action) => $action
                            ->label('Next Step')
                            ->color('primary')
                    )
                    ->previousAction(
                        fn(\Filament\Actions\Action $action) => $action
                            ->label('Back')
                            ->color('gray')
                    ),
            ])
            ->statePath('data');
    }

    public function submit()
    {
        $data = $this->onboardingForm->getState();

        // Create the first branch
        Branch::create([
            'name' => $data['branch_name'],
            'slug' => $data['branch_slug'],
            'address' => $data['branch_address'],
            'phone' => $data['branch_phone'],
            'email' => $data['branch_email'],
            'is_active' => true,
            'tenant_id' => tenant('id'),
        ]);

        // Update tenant settings - use the landlord connection
        /** @var \App\Models\Tenant $tenant */
        $tenant = \App\Models\Tenant::on('landlord')->find(tenant('id'));
        $tenant->update([
            'onboarding_status' => 'active',
            'onboarding_completed' => true,
        ]);

        Notification::make()
            ->title('Setup Complete!')
            ->success()
            ->send();

        // Use Livewire's redirect method
        $this->redirect(route('filament.dashboard.pages.dashboard'));
    }
}

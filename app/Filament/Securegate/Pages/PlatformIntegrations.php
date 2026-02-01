<?php

namespace App\Filament\Securegate\Pages;

use App\Models\PlatformSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use UnitEnum;

class PlatformIntegrations extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static string|UnitEnum|null $navigationGroup = 'Platform Settings';

    protected static ?string $title = 'Integrations & APIs';

    protected string $view = 'filament.securegate.pages.platform-integrations';

    public ?array $data = [];

    protected function getSchemas(): array
    {
        return ['integrationsForm'];
    }

    public function mount(): void
    {
        $settings = PlatformSetting::current();
        $this->integrationsForm->fill($settings->toArray());
    }

    public function integrationsForm(Schema $schema): Schema
    {
        return $schema
            ->schema([
                // Social OAuth Section
                Section::make('Social OAuth Apps')
                    ->description('Configure Google and Facebook Apps to enable "Login with..." and "Connect Account" features.')
                    ->headerActions([
                        Action::make('guide_oauth')
                            ->label('How to get keys?')
                            ->icon('heroicon-m-question-mark-circle')
                            ->modalHeading('Getting Social OAuth Keys')
                            ->modalContent(view('filament.securegate.pages.guides.oauth-guide'))
                            ->modalSubmitAction(false)
                            ->modalCancelAction(false),
                    ])
                    ->schema([
                        Grid::make(2)->schema([
                            Section::make('Google Cloud Project')
                                ->schema([
                                    TextInput::make('plugin_settings.google_client_id')
                                        ->label('Client ID')
                                        ->password()->revealable(),
                                    TextInput::make('plugin_settings.google_client_secret')
                                        ->label('Client Secret')
                                        ->password()->revealable(),
                                    TextInput::make('plugin_settings.google_redirect_uri')
                                        ->label('Redirect URI')
                                        ->default(url('/oauth/google/callback'))
                                        ->readOnly()
                                        ->helperText('Add this to your Authorized Redirect URIs in Google Console.'),
                                ]),
                            Section::make('Meta (Facebook) App')
                                ->schema([
                                    TextInput::make('plugin_settings.facebook_app_id')
                                        ->label('App ID')
                                        ->password()->revealable(),
                                    TextInput::make('plugin_settings.facebook_app_secret')
                                        ->label('App Secret')
                                        ->password()->revealable(),
                                    TextInput::make('plugin_settings.facebook_redirect_uri')
                                        ->label('Redirect URI')
                                        ->default(url('/oauth/facebook/callback'))
                                        ->readOnly()
                                        ->helperText('Add this to your Valid OAuth Redirect URIs in Meta Developer Portal.'),
                                ]),
                        ]),
                    ]),

                // Stripe Platform Section
                Section::make('Stripe Platform Keys')
                    ->description('Keys for Stripe Connect. These allow the platform to facilitate payments for tenants.')
                    ->headerActions([
                        Action::make('guide_stripe')
                            ->label('Where to find keys?')
                            ->icon('heroicon-m-question-mark-circle')
                            ->modalHeading('Stripe Connect Keys')
                            ->modalContent(view('filament.securegate.pages.guides.stripe-guide'))
                            ->modalSubmitAction(false)
                            ->modalCancelAction(false),
                    ])
                    ->schema([
                        Toggle::make('stripe_mode')
                            ->label('Use Live Mode')
                            ->onColor('danger')
                            ->offColor('warning')
                            ->reactive(),

                        \Filament\Schemas\Components\Tabs::make('Keys')
                            ->tabs([
                                \Filament\Schemas\Components\Tabs\Tab::make('Test')
                                    ->schema([
                                        TextInput::make('stripe_settings.test.publishable_key')->label('Test PK')->password()->revealable(),
                                        TextInput::make('stripe_settings.test.secret_key')->label('Test SK')->password()->revealable(),
                                        TextInput::make('stripe_settings.test.client_id')->label('Test Client ID (Connect)')->password()->revealable(),
                                    ]),
                                \Filament\Schemas\Components\Tabs\Tab::make('Live')
                                    ->schema([
                                        TextInput::make('stripe_settings.live.publishable_key')->label('Live PK')->password()->revealable(),
                                        TextInput::make('stripe_settings.live.secret_key')->label('Live SK')->password()->revealable(),
                                        TextInput::make('stripe_settings.live.client_id')->label('Live Client ID (Connect)')->password()->revealable(),
                                    ]),
                            ]),
                    ]),

                // Plugins Section (Directly from ManagePlatformSettings)
                Section::make('Plugins & Analytics')
                    ->collapsed()
                    ->schema([
                        \Filament\Schemas\Components\Tabs::make('Plugins')
                            ->tabs([
                                \Filament\Schemas\Components\Tabs\Tab::make('Google Analytics')
                                    ->schema([
                                        TextInput::make('plugin_settings.google_analytics_id')->label('GA4 Measurement ID'),
                                    ]),
                                \Filament\Schemas\Components\Tabs\Tab::make('Turnstile')
                                    ->schema([
                                        Toggle::make('plugin_settings.cloudflare_turnstile_enabled')->label('Enable'),
                                        TextInput::make('plugin_settings.cloudflare_turnstile_site_key'),
                                        TextInput::make('plugin_settings.cloudflare_turnstile_secret_key'),
                                    ]),
                            ]),
                    ]),
            ])
            ->statePath('integrationData');
    }

    public function save(): void
    {
        $data = $this->integrationsForm->getState();
        $settings = PlatformSetting::current();
        $settings->update($data);

        Notification::make()->title('Integrations saved successfully')->success()->send();
    }
}

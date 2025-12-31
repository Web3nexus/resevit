<?php

namespace App\Filament\Dashboard\Resources;


use BackedEnum;
use App\Filament\Dashboard\Resources\SystemSettingResource\Pages;
use App\Models\ReservationSetting;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;

class SystemSettingResource extends Resource
{
    protected static ?string $model = ReservationSetting::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';

    // Hide from main navigation - will be accessible via user menu
    protected static bool $shouldRegisterNavigation = false;

    /**
     * Redirect to the edit page when accessing the resource root.
     */
    public static function getUrl(?string $name = null, array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?\Illuminate\Database\Eloquent\Model $tenant = null, bool $shouldGuessMissingParameters = false): string
    {
        if ($name === 'index' || $name === null) {
            return route('filament.dashboard.resources.system-settings.edit');
        }

        return parent::getUrl($name, $parameters, $isAbsolute, $panel, $tenant, $shouldGuessMissingParameters);
    }

    protected static ?string $navigationLabel = 'System Settings';

    protected static ?int $navigationSort = 99;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('API Configurations')
                    ->description('Manage external API integrations')
                    ->schema([
                        Forms\Components\TextInput::make('openai_api_key')
                            ->label('OpenAI API Key')
                            ->password()
                            ->revealable(),
                        Forms\Components\TextInput::make('google_maps_api_key')
                            ->label('Google Maps API Key')
                            ->password()
                            ->revealable(),
                    ])->columns(2),

                Section::make('Social Media Integration')
                    ->description('Connect your social media accounts')
                    ->schema([
                        Forms\Components\TextInput::make('facebook_pixel_id')
                            ->label('Facebook Pixel ID'),
                        Forms\Components\TextInput::make('instagram_handle')
                            ->label('Instagram Handle')
                            ->prefix('@'),
                    ])->columns(2),

                Section::make('Notification Settings')
                    ->description('Configure email and staff notifications')
                    ->schema([
                        Forms\Components\Toggle::make('send_confirmation_email')
                            ->label('Send Confirmation Emails')
                            ->helperText('Email guests when reservations are created/confirmed')
                            ->default(true),
                        Forms\Components\Toggle::make('notify_staff_new_reservation')
                            ->label('Notify Staff')
                            ->helperText('Send database notifications to staff for new reservations')
                            ->default(true),
                    ])->columns(2),

                // Keeping auto-confirmation here for now as generic system settings
                Section::make('Reservation Rules')
                    ->description('Configure system-wide reservation rules')
                    ->schema([
                        Forms\Components\Toggle::make('auto_confirm_enabled')
                            ->label('Enable Auto-Confirmation'),
                        Forms\Components\TextInput::make('auto_confirm_hours_threshold')
                            ->label('Auto-Confirm Threshold (Hours)')
                            ->numeric()
                            ->default(24),
                    ])->columns(2),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'edit' => Pages\ManageSystemSettings::route('/'),
        ];
    }
}

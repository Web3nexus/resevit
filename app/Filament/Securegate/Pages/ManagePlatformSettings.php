<?php

namespace App\Filament\Securegate\Pages;

use App\Models\PlatformSetting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;

class ManagePlatformSettings extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static \UnitEnum|string|null $navigationGroup = 'Platform Settings';

    protected string $view = 'filament.securegate.pages.manage-platform-settings';

    protected static ?string $title = 'Platform Settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = PlatformSetting::current();
        $this->form->fill($settings->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Branding')
                    ->description('Update the platform logo and favicon.')
                    ->schema([
                        FileUpload::make('logo_path')
                            ->label('Platform Logo')
                            ->image()
                            ->directory('platform')
                            ->visibility('public')
                            ->imageEditor(),

                        FileUpload::make('favicon_path')
                            ->label('Favicon')
                            ->image()
                            ->directory('platform')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/x-icon', 'image/vnd.microsoft.icon', 'image/png', 'image/svg+xml']),
                    ])
                    ->columns(2),

                Section::make('Landing Page Management')
                    ->description('Customize the visual content of your platform landing page.')
                    ->icon('heroicon-o-home')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('landing_settings.hero_badge')
                                    ->label('Hero Badge Text')
                                    ->placeholder('e.g. THE FUTURE OF DINING'),
                                TextInput::make('landing_settings.hero_title')
                                    ->label('Hero Title')
                                    ->placeholder('e.g. The Operating System for Restaurants')
                                    ->columnSpanFull(),
                                TextInput::make('landing_settings.hero_subtitle')
                                    ->label('Hero Subtitle')
                                    ->placeholder('e.g. Manage reservations, staff, and orders in one place.')
                                    ->columnSpanFull(),
                                TextInput::make('landing_settings.hero_cta_text')
                                    ->label('CTA Button Text')
                                    ->placeholder('e.g. Start Free Trial'),
                                TextInput::make('landing_settings.hero_cta_url')
                                    ->label('CTA Button URL')
                                    ->placeholder('e.g. /register'),
                                TextInput::make('landing_settings.hero_secondary_cta_text')
                                    ->label('Secondary CTA Text')
                                    ->placeholder('e.g. Watch Demo'),
                                TextInput::make('landing_settings.hero_secondary_cta_url')
                                    ->label('Secondary CTA URL')
                                    ->placeholder('e.g. #'),
                                FileUpload::make('landing_settings.hero_image')
                                    ->label('Hero Background/Image')
                                    ->image()
                                    ->directory('platform/landing')
                                    ->visibility('public')
                                    ->columnSpanFull(),
                            ]),
                    ]),

                Section::make('Documents & Documentation')
                    ->description('Set links for user documentation and technical support.')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('landing_settings.docs_url')
                                    ->label('Documentation URL')
                                    ->placeholder('https://docs.resevit.com'),
                                TextInput::make('landing_settings.help_center_url')
                                    ->label('Help Center URL')
                                    ->placeholder('https://support.resevit.com'),
                                TextInput::make('landing_settings.status_page_url')
                                    ->label('Status Page URL')
                                    ->placeholder('https://status.resevit.com'),
                            ]),
                    ]),

                Section::make('Legal & Compliance')
                    ->description('Manage the official legal documents of the platform.')
                    ->icon('heroicon-o-scale')
                    ->schema([
                        \Filament\Forms\Components\Tabs::make('Legal Documents')
                            ->tabs([
                                \Filament\Forms\Components\Tabs\Tab::make('Terms of Service')
                                    ->schema([
                                        \Filament\Forms\Components\RichEditor::make('legal_settings.terms_of_service')
                                            ->label('Terms of Service')
                                            ->required(),
                                    ]),
                                \Filament\Forms\Components\Tabs\Tab::make('Privacy Policy')
                                    ->schema([
                                        \Filament\Forms\Components\RichEditor::make('legal_settings.privacy_policy')
                                            ->label('Privacy Policy')
                                            ->required(),
                                    ]),
                                \Filament\Forms\Components\Tabs\Tab::make('Cookie Policy')
                                    ->schema([
                                        \Filament\Forms\Components\RichEditor::make('legal_settings.cookie_policy')
                                            ->label('Cookie Policy'),
                                    ]),
                                \Filament\Forms\Components\Tabs\Tab::make('GDPR / Data Processing')
                                    ->schema([
                                        \Filament\Forms\Components\RichEditor::make('legal_settings.gdpr')
                                            ->label('GDPR & Data Protection'),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ]),

                Section::make('Social Media Links')
                    ->description('Manage the platform social media profiles.')
                    ->icon('heroicon-o-share')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('landing_settings.social_facebook')
                                    ->label('Facebook URL')
                                    ->placeholder('https://facebook.com/resevit'),
                                TextInput::make('landing_settings.social_twitter')
                                    ->label('Twitter / X URL')
                                    ->placeholder('https://twitter.com/resevit'),
                                TextInput::make('landing_settings.social_instagram')
                                    ->label('Instagram URL')
                                    ->placeholder('https://instagram.com/resevit'),
                                TextInput::make('landing_settings.social_linkedin')
                                    ->label('LinkedIn URL')
                                    ->placeholder('https://linkedin.com/company/resevit'),
                            ]),
                    ]),

                Section::make('Localization')
                    ->description('Select the languages available in the system.')
                    ->schema([
                        CheckboxList::make('supported_languages')
                            ->label('Supported Languages')
                            ->options([
                                'en' => 'English 🇺🇸',
                                'es' => 'Spanish 🇪🇸',
                                'fr' => 'French 🇫🇷',
                                'de' => 'German 🇩🇪',
                                'ar' => 'Arabic 🇸🇦',
                            ])
                            ->columns(3)
                            ->required(),
                    ]),

                Section::make('Promotion Pricing')
                    ->description('Set the prices for directory promotions (Featured Ads).')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('promotion_settings.7_days_price')
                                    ->label('7 Days Price')
                                    ->numeric()
                                    ->prefix('$')
                                    ->default(19.99)
                                    ->required(),
                                TextInput::make('promotion_settings.30_days_price')
                                    ->label('30 Days Price')
                                    ->numeric()
                                    ->prefix('$')
                                    ->default(49.99)
                                    ->required(),
                            ]),
                    ]),

                Section::make('Footer Management')
                    ->description('Organize and manage the links displayed in the site footer.')
                    ->icon('heroicon-o-link')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Repeater::make('footer_settings.others')
                                    ->label('Other Links')
                                    ->schema([
                                        TextInput::make('label')->required(),
                                        TextInput::make('url')->required(),
                                        Toggle::make('is_visible')->default(true),
                                    ])
                                    ->collapsible()
                                    ->columnSpanFull()
                                    ->itemLabel(fn(array $state): ?string => $state['label'] ?? null),
                            ])
                    ]),

                Section::make('Error Pages')
                    ->description('Customize the content of 404 and 500 error pages.')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Section::make('404 Page (Not Found)')
                                    ->schema([
                                        TextInput::make('error_pages.404.title')
                                            ->label('Title')
                                            ->placeholder('Oops! This page doesn\'t exist.')
                                            ->default('Oops! This page doesn\'t exist.'),
                                        TextInput::make('error_pages.404.description')
                                            ->label('Description')
                                            ->placeholder('Let\'s get you back on track.')
                                            ->default('Oops! This page doesn\'t exist. Let\'s get you back on track and find what you are looking for.'),
                                        FileUpload::make('error_pages.404.image')
                                            ->label('Image')
                                            ->image()
                                            ->directory('platform/errors')
                                            ->visibility('public'),
                                    ])->columnSpan(1),

                                Section::make('500 Page (Server Error)')
                                    ->schema([
                                        TextInput::make('error_pages.500.title')
                                            ->label('Title')
                                            ->placeholder('Something went wrong.')
                                            ->default('Something went wrong.'),
                                        TextInput::make('error_pages.500.description')
                                            ->label('Description')
                                            ->placeholder('We\'re working on it.')
                                            ->default('An unexpected error occurred on our server. We\'re working to fix it.'),
                                        FileUpload::make('error_pages.500.image')
                                            ->label('Image')
                                            ->image()
                                            ->directory('platform/errors')
                                            ->visibility('public'),
                                    ])->columnSpan(1),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $settings = PlatformSetting::current();
        $settings->update($data);

        Notification::make()
            ->success()
            ->title('Settings updated successfully.')
            ->send();
    }
}

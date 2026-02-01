<?php

namespace App\Filament\Securegate\Pages;

use App\Models\PlatformSetting;
use BackedEnum;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
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

class ManagePlatformSettings extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string|UnitEnum|null $navigationGroup = 'Platform Settings';

    protected string $view = 'filament.securegate.pages.manage-platform-settings';

    protected static ?string $title = 'Platform Settings';

    public ?array $platformData = [];

    protected function getSchemas(): array
    {
        return ['settingsForm'];
    }

    public function mount(): void
    {
        $settings = PlatformSetting::current();
        $this->platformData = $settings->toArray();
    }

    public function settingsForm(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Public Presence & Landing Page')
                    ->description('Manage your platform identity, landing page content, and legal documents in one place.')
                    ->icon('heroicon-o-globe-alt')
                    ->schema([
                        \Filament\Schemas\Components\Tabs::make('Landing Page Setup')
                            ->tabs([
                                \Filament\Schemas\Components\Tabs\Tab::make('Identity & Theme')
                                    ->icon('heroicon-o-user-circle')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                FileUpload::make('logo_path')
                                                    ->label('Platform Logo')
                                                    ->image()
                                                    ->maxSize(1024)
                                                    ->disk('public')
                                                    ->directory('platform')
                                                    ->visibility('public')
                                                    ->imageEditor()
                                                    ->getUploadedFileUsing(fn ($record) => $record?->logo_path ? \App\Helpers\StorageHelper::getUrl($record->logo_path) : null),

                                                FileUpload::make('favicon_path')
                                                    ->label('Favicon')
                                                    ->image()
                                                    ->maxSize(512)
                                                    ->disk('public')
                                                    ->directory('platform')
                                                    ->visibility('public')
                                                    ->getUploadedFileUsing(fn ($record) => $record?->favicon_path ? \App\Helpers\StorageHelper::getUrl($record->favicon_path) : null)
                                                    ->acceptedFileTypes(['image/x-icon', 'image/vnd.microsoft.icon', 'image/png', 'image/svg+xml']),

                                                \Filament\Forms\Components\Select::make('landing_settings.active_theme')
                                                    ->label('Active Theme')
                                                    ->options([
                                                        'default' => 'Classic Elegance',
                                                        'modern' => 'Modern Dark (GitHub Style)',
                                                        'calendly' => 'Calendly Style (Clean & Professional)',
                                                    ])
                                                    ->default('default')
                                                    ->required()
                                                    ->columnSpanFull(),
                                            ]),

                                        Section::make('Hero Section')
                                            ->compact()
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
                                                Grid::make(2)
                                                    ->schema([
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
                                                    ]),
                                                FileUpload::make('landing_settings.hero_image')
                                                    ->label('Hero Background/Image')
                                                    ->image()
                                                    ->maxSize(2048)
                                                    ->directory('platform/landing')
                                                    ->visibility('public')
                                                    ->getUploadedFileUsing(fn ($record) => $record?->landing_settings['hero_image'] ?? null ? \App\Helpers\StorageHelper::getUrl($record->landing_settings['hero_image']) : null)
                                                    ->columnSpanFull(),
                                            ]),
                                    ]),

                                \Filament\Schemas\Components\Tabs\Tab::make('Links & Socials')
                                    ->icon('heroicon-o-link')
                                    ->schema([
                                        Section::make('Documentation & Support')
                                            ->description('Set links for user documentation and technical support.')
                                            ->compact()
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

                                        Section::make('Social Media Profiles')
                                            ->description('Manage the platform social media profiles.')
                                            ->compact()
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
                                    ]),

                                \Filament\Schemas\Components\Tabs\Tab::make('Legal Documents')
                                    ->icon('heroicon-o-scale')
                                    ->schema([
                                        \Filament\Schemas\Components\Tabs::make('Legal Tabs')
                                            ->tabs([
                                                \Filament\Schemas\Components\Tabs\Tab::make('Terms of Service')
                                                    ->schema([
                                                        \Filament\Forms\Components\RichEditor::make('legal_settings.terms_of_service')
                                                            ->label('Terms of Service'),
                                                    ]),
                                                \Filament\Schemas\Components\Tabs\Tab::make('Privacy Policy')
                                                    ->schema([
                                                        \Filament\Forms\Components\RichEditor::make('legal_settings.privacy_policy')
                                                            ->label('Privacy Policy'),
                                                    ]),
                                                \Filament\Schemas\Components\Tabs\Tab::make('Cookie Policy')
                                                    ->schema([
                                                        \Filament\Forms\Components\RichEditor::make('legal_settings.cookie_policy')
                                                            ->label('Cookie Policy'),
                                                    ]),
                                                \Filament\Schemas\Components\Tabs\Tab::make('GDPR / Data Processing')
                                                    ->schema([
                                                        \Filament\Forms\Components\RichEditor::make('legal_settings.gdpr')
                                                            ->label('GDPR & Data Protection'),
                                                    ]),
                                            ]),
                                    ]),
                            ])
                            ->columnSpanFull(),
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

                Section::make('Global Referral & Affiliate Settings')
                    ->description('Manage referral programs and commission thresholds for Influencers, Business Owners, and Customers.')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('promotion_settings.min_withdrawal_amount')
                                    ->label('Min Withdrawal')
                                    ->numeric()
                                    ->prefix('$')
                                    ->default(50)
                                    ->required(),

                                Toggle::make('promotion_settings.affiliate_enabled')
                                    ->label('Influencer Program')
                                    ->default(true),

                                Toggle::make('promotion_settings.owner_referral_enabled')
                                    ->label('Owner Referrals')
                                    ->default(true),

                                Toggle::make('promotion_settings.customer_referral_enabled')
                                    ->label('Customer Referrals')
                                    ->default(true),
                            ]),

                        Grid::make(3)
                            ->schema([
                                TextInput::make('promotion_settings.influencer_commission')
                                    ->label('Influencer Commission')
                                    ->numeric()
                                    ->prefix('$')
                                    ->helperText('Fixed amount per successful referral'),

                                TextInput::make('promotion_settings.owner_commission')
                                    ->label('Owner Commission')
                                    ->numeric()
                                    ->prefix('$')
                                    ->helperText('Fixed amount per successful referral'),

                                TextInput::make('promotion_settings.customer_commission')
                                    ->label('Customer Commission')
                                    ->numeric()
                                    ->prefix('$')
                                    ->helperText('Fixed amount per successful referral'),
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
                                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
                            ]),
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
                                            ->visibility('public')
                                            ->getUploadedFileUsing(fn ($record) => $record?->error_pages['404']['image'] ?? null ? \App\Helpers\StorageHelper::getUrl($record->error_pages['404']['image']) : null),
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
                                            ->visibility('public')
                                            ->getUploadedFileUsing(fn ($record) => $record?->error_pages['500']['image'] ?? null ? \App\Helpers\StorageHelper::getUrl($record->error_pages['500']['image']) : null),
                                    ])->columnSpan(1),
                            ]),
                    ]),
            ])
            ->statePath('platformData');
    }

    public function save(): void
    {
        $data = $this->settingsForm->getState();

        $settings = PlatformSetting::current();
        $settings->update($data);

        // Sync to Landing Page (Home > Hero)
        $this->syncLandingPage($data);

        // Sync Legal Pages
        $this->syncLegalPages($data);

        Notification::make()
            ->success()
            ->title('Settings updated and synced to Landing Page.')
            ->send();
    }

    protected function syncLandingPage(array $data): void
    {
        try {
            // 1. Find or Create Home Page
            $homePage = \App\Models\LandingPage::firstOrCreate(
                ['slug' => 'home'],
                ['title' => 'Home', 'is_active' => true, 'meta_title' => 'Home']
            );

            // 2. Find or Create Hero Section
            $heroSection = \App\Models\LandingSection::firstOrCreate(
                ['landing_page_id' => $homePage->id, 'type' => 'hero'],
                ['order' => 0, 'is_active' => true]
            );

            // 3. Prepare Content JSON
            $content = $heroSection->content ?? [];
            $landingSettings = $data['landing_settings'] ?? [];

            $content['description'] = $landingSettings['hero_subtitle'] ?? $content['description'] ?? null;
            $content['cta_text'] = $landingSettings['hero_cta_text'] ?? $content['cta_text'] ?? null;
            $content['cta_url'] = $landingSettings['hero_cta_url'] ?? $content['cta_url'] ?? null;
            $content['secondary_cta_text'] = $landingSettings['hero_secondary_cta_text'] ?? $content['secondary_cta_text'] ?? null;
            $content['secondary_cta_url'] = $landingSettings['hero_secondary_cta_url'] ?? $content['secondary_cta_url'] ?? null;

            // 4. Update Section Main Fields
            $heroSection->update([
                'title' => $landingSettings['hero_title'] ?? $heroSection->title,
                'subtitle' => $landingSettings['hero_badge'] ?? $heroSection->subtitle, // Badge maps to subtitle
                'content' => $content,
            ]);

            // 5. Sync Image to First Item (Icon field)
            if (! empty($landingSettings['hero_image'])) {
                $imageUrl = \App\Helpers\StorageHelper::getUrl($landingSettings['hero_image']);

                $heroItem = \App\Models\LandingItem::firstOrCreate(
                    ['landing_section_id' => $heroSection->id],
                    ['order' => 0, 'is_active' => true]
                );

                $heroItem->update(['icon' => $imageUrl]);
            }

        } catch (\Exception $e) {
            // Log error silently or notify admin - strictly for sync resilience
            \Illuminate\Support\Facades\Log::error('Failed to sync Platform Settings to Landing Page: '.$e->getMessage());
        }
    }

    protected function syncLegalPages(array $data): void
    {
        $legalSettings = $data['legal_settings'] ?? [];
        $mapping = [
            'terms_of_service' => [
                'slug' => 'terms',
                'title' => 'Terms of Service',
            ],
            'privacy_policy' => [
                'slug' => 'privacy',
                'title' => 'Privacy Policy',
            ],
            'cookie_policy' => [
                'slug' => 'cookie-policy',
                'title' => 'Cookie Policy',
            ],
            'gdpr' => [
                'slug' => 'gdpr',
                'title' => 'GDPR Compliance',
            ],
        ];

        foreach ($mapping as $settingKey => $pageInfo) {
            if (empty($legalSettings[$settingKey])) {
                continue;
            }

            try {
                // 1. Find or Create Page
                $page = \App\Models\LandingPage::firstOrCreate(
                    ['slug' => $pageInfo['slug']],
                    ['title' => $pageInfo['title'], 'is_active' => true, 'meta_title' => $pageInfo['title']]
                );

                // 2. Find or Create Text Content Section
                $section = \App\Models\LandingSection::firstOrCreate(
                    ['landing_page_id' => $page->id, 'type' => 'text_content'],
                    ['order' => 0, 'is_active' => true]
                );

                // 3. Update Content
                // Store the rich text content inside the 'content' JSON column under a 'body' key
                $content = $section->content ?? [];
                $content['body'] = $legalSettings[$settingKey];

                $section->update([
                    'title' => $pageInfo['title'],
                    'content' => $content,
                ]);

            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to sync legal page {$pageInfo['slug']}: ".$e->getMessage());
            }
        }
    }
}

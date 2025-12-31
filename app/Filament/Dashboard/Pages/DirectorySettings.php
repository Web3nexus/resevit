<?php

namespace App\Filament\Dashboard\Pages;


use BackedEnum;
use App\Models\BusinessCategory;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;

use App\Models\PlatformSetting;
use App\Models\Transaction;

class DirectorySettings extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected string $view = 'filament.dashboard.pages.directory-settings';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static ?string $navigationLabel = 'Directory Profile';

    protected static ?string $title = 'Directory & SEO Profile';

    protected static ?int $navigationSort = 90;

    public ?array $profileData = [];

    public $prices = [
        7 => 19.99,
        30 => 49.99,
    ];

    public static function canAccess(): bool
    {
        return has_feature('directory_listing');
    }

    public function mount(): void
    {
        $tenant = tenant();
        $this->profileData = [
            'is_public' => $tenant->is_public,
            'business_category_id' => $tenant->business_category_id,
            'description' => $tenant->description,
            'cover_image' => $tenant->cover_image,
            'seo_title' => $tenant->seo_title,
            'seo_description' => $tenant->seo_description,
        ];

        // Load prices from platform settings
        $settings = PlatformSetting::current();
        if (isset($settings->promotion_settings['7_days_price'])) {
            $this->prices[7] = (float) $settings->promotion_settings['7_days_price'];
        }
        if (isset($settings->promotion_settings['30_days_price'])) {
            $this->prices[30] = (float) $settings->promotion_settings['30_days_price'];
        }
    }

    protected function getSchemas(): array
    {
        return [
            'profileForm',
        ];
    }

    public function profileForm(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Public Listing')
                    ->description('Manage how your business appears in our public directory.')
                    ->schema([
                        Forms\Components\Toggle::make('is_public')
                            ->label('Visible in Directory')
                            ->helperText('Uncheck this to hide your business from the public directory.')
                            ->default(true),
                        Forms\Components\Select::make('business_category_id')
                            ->label('Business Category')
                            ->options(BusinessCategory::where('is_active', true)->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\FileUpload::make('cover_image')
                            ->label('Cover Banner')
                            ->image()
                            ->directory('businesses/covers')
                            ->imageEditor()
                            ->getUploadedFileUrlUsing(fn($record) => \App\Helpers\StorageHelper::getUrl($record->cover_image ?? $record->profileData['cover_image'] ?? null)),
                        Forms\Components\Textarea::make('description')
                            ->label('Profile Description')
                            ->rows(4)
                            ->placeholder('Describe your business to potential customers...')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Search Engine Optimization (SEO)')
                    ->description('Boost your visibility on Google and other search engines.')
                    ->schema([
                        Forms\Components\TextInput::make('seo_title')
                            ->label('SEO Title')
                            ->placeholder('e.g. Best Italian Restaurant in Rome | Name')
                            ->maxLength(60),
                        Forms\Components\Textarea::make('seo_description')
                            ->label('SEO Description')
                            ->rows(3)
                            ->placeholder('A short summary for search engine results...')
                            ->maxLength(160),
                    ]),
            ])
            ->statePath('profileData');
    }

    public function updateProfile(): void
    {
        $data = $this->profileForm->getState();
        $tenant = tenant();
        $tenant->update($data);

        Notification::make()
            ->success()
            ->title('Directory profile updated')
            ->send();
    }

    public function buyPromotion(int $days): void
    {
        $user = auth()->user();
        $price = $this->prices[$days] ?? 0;

        if ($user->wallet_balance < $price) {
            Notification::make()
                ->danger()
                ->title("Insufficient Funds")
                ->body("You need $" . number_format($price, 2) . " in your wallet. Your current balance is $" . number_format($user->wallet_balance, 2))
                ->send();
            return;
        }

        $tenant = tenant();
        $currentExpiry = $tenant->promotion_expires_at ? Carbon::parse($tenant->promotion_expires_at) : now();

        if ($currentExpiry->isPast()) {
            $currentExpiry = now();
        }

        // Deduct from wallet
        $user->decrement('wallet_balance', $price);

        // Update tenant
        $tenant->update([
            'is_sponsored' => true,
            'promotion_expires_at' => $currentExpiry->addDays($days),
        ]);

        // Create Transaction
        Transaction::create([
            'user_id' => $user->id,
            'transactionable_type' => get_class($tenant),
            'transactionable_id' => $tenant->id,
            'amount' => $price,
            'type' => 'payment',
            'status' => 'completed',
            'description' => "Purchased directory promotion ({$days} days) for {$tenant->name}",
            'metadata' => [
                'days' => $days,
                'price' => $price,
                'tenant_name' => $tenant->name,
            ],
        ]);

        Notification::make()
            ->success()
            ->title("Ad Campaign Activated!")
            ->body("Your business is now featured for {$days} days. $" . number_format($price, 2) . " was deducted from your wallet.")
            ->send();
    }
}

<?php

namespace App\Filament\Dashboard\Pages;

use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use UnitEnum;

class WhitelabelSettings extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-swatch';

    protected static ?string $navigationLabel = 'Whitelabel Settings';

    protected static ?string $title = 'Whitelabel Branding';

    protected static string|UnitEnum|null $navigationGroup = 'System Management';

    protected static ?int $navigationSort = 100;

    protected string $view = 'filament.dashboard.pages.whitelabel-settings';

    public ?array $brandingData = [];

    public static function canAccess(): bool
    {
        return has_feature('whitelabel');
    }

    protected function getSchemas(): array
    {
        return ['brandingForm'];
    }

    public function mount(): void
    {
        $tenant = tenant();

        $this->brandingData = [
            'whitelabel_active' => $tenant->whitelabel_active,
            'whitelabel_logo' => $tenant->whitelabel_logo,
            'website_custom_domain' => $tenant->website_custom_domain,
            'dashboard_custom_domain' => $tenant->dashboard_custom_domain,
        ];
    }

    public function brandingForm(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Professional Domain Management')
                    ->description('Assign custom domains for your restaurant website and whitelabeled dashboard.')
                    ->columns(2)
                    ->schema([
                        Toggle::make('whitelabel_active')
                            ->label('Enable Whitelabeling')
                            ->helperText('Enable this to allow custom dashboard access and custom branding.')
                            ->reactive()
                            ->columnSpanFull(),

                        FileUpload::make('whitelabel_logo')
                            ->label('Custom Dashboard Logo')
                            ->image()
                            ->directory('whitelabel-logos')
                            ->visibility('public')
                            ->helperText('Replaces the platform logo on your dashboard.')
                            ->getUploadedFileUsing(fn ($record) => \App\Helpers\StorageHelper::getUrl($record->whitelabel_logo ?? $record->data['whitelabel_logo'] ?? null))
                            ->visible(fn ($get) => $get('whitelabel_active'))
                            ->columnSpanFull(),

                        \Filament\Forms\Components\TextInput::make('website_custom_domain')
                            ->label('Website Custom Domain')
                            ->placeholder('e.g., www.myrestaurant.com')
                            ->helperText('Point this to your public-facing business website.')
                            ->columnSpan(1),

                        \Filament\Forms\Components\TextInput::make('dashboard_custom_domain')
                            ->label('Dashboard Custom Domain')
                            ->placeholder('e.g., app.myrestaurant.com')
                            ->helperText('Point this to your whitelabeled administration dashboard.')
                            ->columnSpan(1)
                            ->visible(fn ($get) => $get('whitelabel_active')),
                    ]),

                Section::make('DNS Record Setup Instructions')
                    ->description('Follow these steps to point your custom domains to our platform.')
                    ->schema([
                        \Filament\Forms\Components\Placeholder::make('dns_instructions')
                            ->label('')
                            ->content(function () {
                                $ip = $_SERVER['SERVER_ADDR'] ?? '127.0.0.1';
                                $host = parse_url(config('app.url'), PHP_URL_HOST);
                                $ns1 = 'ns1.'.$host;
                                $ns2 = 'ns2.'.$host;

                                return new \Illuminate\Support\HtmlString("
                                    <div class='space-y-6 text-sm'>
                                        <div class='p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800 shadow-sm'>
                                            <h4 class='font-bold mb-2 text-green-800 dark:text-green-300 flex items-center gap-2'>
                                                <svg class='w-5 h-5' fill='green' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z' clip-rule='evenodd'></path></svg>
                                                Option A: CNAME Record (Best Choice - Highly Recommended)
                                            </h4>
                                            <p class='text-xs mb-3 text-green-700 dark:text-green-400'>
                                                <b>Why?</b> CNAMEs are more stable. If our server IP ever changes, your domain will keep working automatically without you needing to update any settings.
                                            </p>
                                            <div class='space-y-2'>
                                                <label class='text-[10px] uppercase font-bold text-gray-400'>Point to Host</label>
                                                <code class='block font-mono bg-white dark:bg-gray-900 p-2 rounded border border-gray-100 dark:border-gray-700'>{$host}</code>
                                            </div>
                                        </div>

                                        <div class='grid grid-cols-1 md:grid-cols-2 gap-4 opacity-80'>
                                            <div class='p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700'>
                                                <h4 class='font-bold mb-2 text-gray-500'>Option B: A Record (IP)</h4>
                                                <p class='text-[11px] text-gray-400 mb-2'>Use only if your registrar doesn't allow CNAME for naked domains.</p>
                                                <code class='block font-mono bg-white dark:bg-gray-900 p-2 rounded border border-gray-100 dark:border-gray-700'>{$ip}</code>
                                            </div>

                                            <div class='p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700'>
                                                <h4 class='font-bold mb-2 text-gray-500'>Option C: Nameservers (NS)</h4>
                                                <p class='text-[11px] text-gray-400 mb-2'>Full delegation to our platform.</p>
                                                <div class='space-y-1 text-xs'>
                                                    <code class='block font-mono bg-white dark:bg-gray-900 p-1 rounded'>{$ns1}</code>
                                                    <code class='block font-mono bg-white dark:bg-gray-900 p-1 rounded'>{$ns2}</code>
                                                </div>
                                            </div>
                                        </div>

                                        <div class='p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-300'>
                                            <h5 class='font-bold mb-1 flex items-center gap-2 text-xs'>
                                                <svg class='w-4 h-4' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z' clip-rule='evenodd'></path></svg>
                                                Automatic Routing
                                            </h5>
                                            <p class='text-[11px]'>Once DNS is pointed, our system detects your domain and serves your branded experience automatically. No manual server work required.</p>
                                        </div>
                                    </div>
                                ");
                            }),
                    ])
                    ->collapsible(),
            ])
            ->statePath('brandingData');
    }

    public function save(): void
    {
        $state = $this->brandingForm->getState();

        /** @var \App\Models\Tenant $tenant */
        $tenant = \App\Models\Tenant::on('landlord')->find(tenant('id'));
        $tenant->fill([
            'whitelabel_active' => $state['whitelabel_active'],
            'whitelabel_logo' => $state['whitelabel_logo'],
            'website_custom_domain' => $state['website_custom_domain'],
            'dashboard_custom_domain' => $state['dashboard_custom_domain'],
        ])->save();

        Notification::make()
            ->success()
            ->title('Whitelabel settings saved.')
            ->send();
    }
}

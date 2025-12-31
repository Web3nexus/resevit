<?php

namespace App\Filament\Dashboard\Pages;


use BackedEnum;
use App\Models\SitePage;
use App\Models\Category;
use App\Services\AI\WebsiteGeneratorService;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ColorPicker;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;

class WebsiteBuilder extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected string $view = 'filament.dashboard.pages.website-builder';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationLabel = 'Website Builder';

    protected static ?string $title = 'AI Website Builder';

    protected static ?int $navigationSort = 100;

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return has_feature('website_builder');
    }

    public function mount(): void
    {
        $page = SitePage::home()->first();

        $this->data = [
            'name' => $page->name ?? 'Home',
            'is_published' => $page->is_published ?? false,
            'custom_domain' => tenant()->website_custom_domain,
            'blocks' => $page->config['blocks'] ?? [],
        ];
    }

    protected function getActions(): array
    {
        return [
            \Filament\Actions\Action::make('view_live')
                ->label('View Live Site')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(route('home'), shouldOpenInNewTab: true)
                ->color('gray'),
        ];
    }

    protected function getSchemas(): array
    {
        return [
            'form',
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Page Settings')
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        Toggle::make('is_published')
                            ->label('Published')
                            ->helperText('Enable this to make the website live on your domain.'),
                    ])->columns(2),

                Section::make('Domain Settings')
                    ->description('Connect a custom domain to your website.')
                    ->schema([
                        has_feature('custom_domain')
                        ? TextInput::make('custom_domain')
                            ->label('Custom Domain')
                            ->placeholder('example.com')
                            ->helperText('To use a custom domain, point its A record to your server IP or CNAME to ' . parse_url(config('app.url'), PHP_URL_HOST) . '. Enter the domain without http:// or https://.')
                            ->regex('/^(?!:\/\/)([a-zA-Z0-9-_]+\.)+[a-zA-Z0-9][a-zA-Z0-9-_]+$/')
                        : \Filament\Forms\Components\Placeholder::make('custom_domain_locked')
                            ->label('Custom Domain')
                            ->content('Upgrade to the Pro plan to connect a custom domain.')
                            ->extraAttributes(['class' => 'text-slate-500 italic']),
                    ]),

                Section::make('Website Blocks')
                    ->description('Use AI to generate or manually add and customize sections.')
                    ->schema([
                        Builder::make('blocks')
                            ->label('Sections')
                            ->blocks([
                                Block::make('hero')
                                    ->icon('heroicon-o-sparkles')
                                    ->schema([
                                        TextInput::make('headline')->required(),
                                        TextInput::make('subheadline'),
                                        TextInput::make('cta_text'),
                                        TextInput::make('cta_url'),
                                        FileUpload::make('background_image')
                                            ->image()
                                            ->directory('website-images')
                                            ->getUploadedFileUrlUsing(fn($file) => \App\Helpers\StorageHelper::getUrl($file)),
                                        ColorPicker::make('overlay_color')->label('Overlay Color')->rgba(),
                                        ColorPicker::make('text_color')->label('Text Color'),
                                    ]),
                                Block::make('about')
                                    ->icon('heroicon-o-identification')
                                    ->schema([
                                        TextInput::make('title')->required(),
                                        Textarea::make('content')->required(),
                                        FileUpload::make('image')
                                            ->image()
                                            ->directory('website-images')
                                            ->getUploadedFileUrlUsing(fn($file) => \App\Helpers\StorageHelper::getUrl($file)),
                                        ColorPicker::make('background_color')->label('Background Color'),
                                    ]),
                                Block::make('dynamic_menu')
                                    ->label('Dynamic Menu (Sync)')
                                    ->icon('heroicon-o-arrow-path')
                                    ->schema([
                                        TextInput::make('title')->required()->default('Our Menu'),
                                        TextInput::make('subtitle')->default('Freshly prepared for you.'),
                                        \Filament\Forms\Components\Select::make('category_ids')
                                            ->label('Select Categories to Display')
                                            ->multiple()
                                            ->options(fn() => Category::where('is_active', true)->pluck('name', 'id'))
                                            ->required(),
                                        ColorPicker::make('background_color')->label('Background Color'),
                                    ]),
                                Block::make('menu_preview')
                                    ->icon('heroicon-o-shopping-bag')
                                    ->schema([
                                        TextInput::make('title')->required(),
                                        Repeater::make('items')
                                            ->schema([
                                                TextInput::make('name')->required(),
                                                TextInput::make('price'),
                                                Textarea::make('description'),
                                            ])->columns(2),
                                        ColorPicker::make('background_color')->label('Background Color'),
                                    ]),
                                Block::make('reservation_cta')
                                    ->icon('heroicon-o-calendar')
                                    ->schema([
                                        TextInput::make('headline')->required(),
                                        TextInput::make('button_text')->required(),
                                        FileUpload::make('background_image')
                                            ->image()
                                            ->directory('website-images')
                                            ->getUploadedFileUrlUsing(fn($file) => \App\Helpers\StorageHelper::getUrl($file)),
                                        ColorPicker::make('text_color')->label('Text Color'),
                                        ColorPicker::make('button_color')->label('Button Color'),
                                    ]),
                                Block::make('contact')
                                    ->icon('heroicon-o-phone')
                                    ->schema([
                                        TextInput::make('address'),
                                        TextInput::make('phone'),
                                        TextInput::make('email'),
                                        ColorPicker::make('background_color')->label('Background Color'),
                                    ]),
                                Block::make('reservation_form')
                                    ->icon('heroicon-o-document-check')
                                    ->schema([
                                        TextInput::make('title')->required(),
                                        TextInput::make('description'),
                                        ColorPicker::make('background_color')->label('Background Color'),
                                    ]),
                            ])
                            ->collapsible()
                            ->collapsed()
                            ->cloneable(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        SitePage::updateOrCreate(
            ['slug' => 'index'],
            [
                'name' => $state['name'],
                'is_published' => $state['is_published'],
                'config' => ['blocks' => $state['blocks']],
            ]
        );

        // Handle Custom Domain Saving
        if (has_feature('custom_domain')) {
            tenant()->update([
                'website_custom_domain' => $state['custom_domain'] ?? null,
            ]);
        }

        Notification::make()
            ->success()
            ->title('Website saved successfully')
            ->send();
    }

    public function generateWithAi(WebsiteGeneratorService $generator): void
    {
        $blocks = $generator->generateLayout();

        $this->data['blocks'] = $blocks;

        Notification::make()
            ->success()
            ->title('Website generated with AI!')
            ->body('Review and publish your changes below.')
            ->send();
    }
}

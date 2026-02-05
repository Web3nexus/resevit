<?php

namespace App\Filament\Dashboard\Pages;

use App\Models\TenantWebsite;
use App\Models\WebsiteTemplate;
use Filament\Actions\Action;
use Filament\Schemas\Components\Tabs as SchemaTabs;
use Filament\Schemas\Components\Tabs\Tab as SchemaTab;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;

class WebsiteBuilder extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-globe-alt';

    protected string $view = 'filament.dashboard.pages.website-builder';

    protected static ?string $navigationLabel = 'Website Builder';

    protected static string|\UnitEnum|null $navigationGroup = 'Marketing';

    public ?WebsiteTemplate $selectedTemplate = null;

    public ?TenantWebsite $website = null;

    public bool $browsingTemplates = false;

    public ?array $builderData = [];

    public bool $isEditing = false;

    protected function getSchemas(): array
    {
        return ['builderForm'];
    }

    public function mount(): void
    {
        $this->website = TenantWebsite::where('tenant_id', tenant('id'))->with('template')->first();

        if ($this->website) {
            $this->selectedTemplate = $this->website->template;
            $this->builderData = $this->website->content ?? [];
        }
    }

    public function builderForm(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Section::make('Global Styling')
                    ->schema([
                        \Filament\Forms\Components\ColorPicker::make('settings.primary_color')
                            ->label('Primary Brand Color')
                            ->default('#0B132B'),
                        \Filament\Forms\Components\Select::make('settings.border_radius')
                            ->label('Border Radius')
                            ->options([
                                'none' => 'Sharp',
                                'sm' => 'Small',
                                'md' => 'Medium',
                                'lg' => 'Large',
                                'xl' => 'Extra Large',
                                '2xl' => 'Double Extra Large',
                                '3xl' => 'Rounded (Extra)',
                                'full' => 'Pill',
                            ])
                            ->default('lg'),
                        \Filament\Forms\Components\Select::make('settings.font_family')
                            ->label('Font Family')
                            ->options([
                                'Inter' => 'Modern Sans (Inter)',
                                'Outfit' => 'Clean Sans (Outfit)',
                                'Playfair Display' => 'Classic Serif (Playfair)',
                                'Montserrat' => 'Elegant Sans (Montserrat)',
                            ])
                            ->default('Outfit'),
                    ])
                    ->collapsible()
                    ->collapsed(),
                SchemaTabs::make('Website Sections')
                    ->tabs([
                        SchemaTab::make('Header & Branding')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('business_name')
                                    ->required(),
                                \Filament\Forms\Components\FileUpload::make('logo')
                                    ->image()
                                    ->directory('website/logos'),
                            ]),
                        SchemaTab::make('Hero Section')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('hero_title')
                                    ->label('Hero Title'),
                                \Filament\Forms\Components\TextInput::make('hero_subtitle')
                                    ->label('Hero Subtitle'),
                                \Filament\Forms\Components\TextInput::make('hero_tagline')
                                    ->label('Hero Tagline'),
                                \Filament\Forms\Components\TextInput::make('hero_promo')
                                    ->label('Promo Badge (e.g. 20% OFF)'),
                                \Filament\Forms\Components\FileUpload::make('hero_image')
                                    ->image()
                                    ->directory('website/hero'),
                            ]),
                        SchemaTab::make('About Section')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('about_title')
                                    ->label('About Title'),
                                \Filament\Forms\Components\Textarea::make('about_text')
                                    ->label('About Text')
                                    ->rows(5),
                                \Filament\Forms\Components\FileUpload::make('about_image')
                                    ->image()
                                    ->directory('website/about'),
                            ]),
                        SchemaTab::make('Menu & Products')
                            ->schema([
                                \Filament\Forms\Components\Repeater::make('menu_sections')
                                    ->label('Menu Highlights')
                                    ->schema([
                                        \Filament\Forms\Components\TextInput::make('title')->required(),
                                        \Filament\Forms\Components\TextInput::make('price'),
                                        \Filament\Forms\Components\FileUpload::make('image')->image(),
                                    ])
                                    ->collapsible()
                                    ->itemLabel(fn(array $state): ?string => $state['title'] ?? null),
                                \Filament\Forms\Components\Repeater::make('special_menu')
                                    ->label('Special Menu')
                                    ->schema([
                                        \Filament\Forms\Components\TextInput::make('name')->required(),
                                        \Filament\Forms\Components\TextInput::make('desc'),
                                        \Filament\Forms\Components\TextInput::make('price'),
                                    ])
                                    ->collapsible()
                                    ->itemLabel(fn(array $state): ?string => $state['name'] ?? null),
                            ]),
                        SchemaTab::make('Services & Features')
                            ->schema([
                                \Filament\Forms\Components\Repeater::make('services')
                                    ->schema([
                                        \Filament\Forms\Components\TextInput::make('title')->required(),
                                        \Filament\Forms\Components\Textarea::make('desc'),
                                        \Filament\Forms\Components\TextInput::make('icon')->helperText('Icon name (e.g. coffee, leaf, truck)'),
                                    ])
                                    ->collapsible()
                                    ->itemLabel(fn(array $state): ?string => $state['title'] ?? null),
                                \Filament\Forms\Components\Repeater::make('how_it_works')
                                    ->label('How it Works')
                                    ->schema([
                                        \Filament\Forms\Components\TextInput::make('title')->required(),
                                        \Filament\Forms\Components\Textarea::make('desc'),
                                        \Filament\Forms\Components\TextInput::make('icon'),
                                    ])
                                    ->collapsible()
                                    ->itemLabel(fn(array $state): ?string => $state['title'] ?? null),
                            ]),
                        SchemaTab::make('Footer')
                            ->schema([
                                \Filament\Forms\Components\Textarea::make('footer_text')
                                    ->label('Footer Text'),
                                \Filament\Forms\Components\Repeater::make('working_hours')
                                    ->simple(\Filament\Forms\Components\TextInput::make('text')),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('builderData');
    }

    public function selectTemplate(int $templateId)
    {
        $template = WebsiteTemplate::findOrFail($templateId);
        $this->selectedTemplate = $template;
        $this->builderData = $template->default_content;
        $this->browsingTemplates = false;

        if ($this->website) {
            $this->website->update([
                'website_template_id' => $template->id,
                'content' => $template->default_content,
            ]);
            Notification::make()->title('Template Switched')->success()->send();

            return redirect()->to(request()->header('Referer'));
        }
    }

    public function cancelBrowsing()
    {
        if ($this->website) {
            $this->selectedTemplate = $this->website->template;
            $this->builderData = $this->website->content;
        } else {
            $this->selectedTemplate = null;
        }
        $this->browsingTemplates = false;
    }

    public function createWebsite()
    {
        if (!$this->selectedTemplate) {
            return;
        }

        // Fetch business settings
        $settings = \App\Models\ReservationSetting::getInstance();

        $content = $this->selectedTemplate->default_content;

        // Inject real data
        $content['business_name'] = $settings->business_name ?: ($content['business_name'] ?? tenant('id'));
        if ($settings->business_logo) {
            $content['logo'] = $settings->business_logo; // Assuming template uses 'logo' key or we add it
        }
        if ($settings->business_phone) {
            $content['phone'] = $settings->business_phone;
        }
        // Basic Footer Text Injection
        $content['footer_text'] = '© ' . date('Y') . ' ' . $content['business_name'] . '. All Rights Reserved.';

        // Save
        $this->website = TenantWebsite::updateOrCreate(
            ['tenant_id' => tenant('id')],
            [
                'website_template_id' => $this->selectedTemplate->id,
                'content' => $content,
                'is_published' => false,
            ]
        );

        $this->builderData = $this->website->content; // Load into form

        Notification::make()->title('Template Selected')->success()->send();

        return redirect()->to(request()->header('Referer'));
    }

    public function save()
    {
        $data = $this->builderForm->getState();

        if ($this->website) {
            $this->website->update([
                'content' => $data,
            ]);
        }

        Notification::make()->title('Website content saved')->success()->send();

        // Refresh the page to reload the iframe with new content
        return redirect()->to(request()->header('Referer'));
    }

    public function toggleEditor()
    {
        $this->isEditing = !$this->isEditing;
        if ($this->isEditing) {
            $this->builderData = $this->website->content;
        }
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('publish')
                ->label('Publish Website')
                ->color('success')
                ->icon('heroicon-o-rocket-launch')
                ->action(function () {
                    $this->website->update([
                        'is_published' => true,
                        'published_at' => now(),
                    ]);
                    Notification::make()->title('Website Published!')->success()->send();
                })
                ->visible(fn() => $this->website !== null && !$this->website->is_published),
            Action::make('unpublish')
                ->label('Unpublish')
                ->color('danger')
                ->icon('heroicon-o-eye-slash')
                ->requiresConfirmation()
                ->action(function () {
                    $this->website->update(['is_published' => false]);
                    Notification::make()->title('Website Unpublished')->warning()->send();
                })
                ->visible(fn() => $this->website !== null && $this->website->is_published),
            Action::make('preview')
                ->label('Preview Website')
                ->color('gray')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn() => 'https://' . tenant('slug') . '.' . config('tenancy.preview_domain'), shouldOpenInNewTab: true)
                ->visible(fn() => $this->website !== null),
            Action::make('changeTemplate')
                ->label('Change Template')
                ->color('gray')
                ->icon('heroicon-o-swatch')
                ->action(fn() => $this->browsingTemplates = true)
                ->visible(fn() => $this->website !== null && !$this->browsingTemplates),
        ];
    }

    protected function getViewData(): array
    {
        return [
            'templates' => WebsiteTemplate::where('is_active', true)->get(),
        ];
    }
}

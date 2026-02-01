<?php

namespace App\Filament\Dashboard\Pages;

use App\Models\TenantWebsite;
use App\Models\WebsiteTemplate;
use Filament\Actions\Action;
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
                \Filament\Forms\Components\Builder::make('sections')
                    ->blocks([
                        \Filament\Forms\Components\Builder\Block::make('nav')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('logo_text')->required(),
                                \Filament\Forms\Components\Repeater::make('links')
                                    ->schema([
                                        \Filament\Forms\Components\TextInput::make('label')->required(),
                                        \Filament\Forms\Components\TextInput::make('url')->required(),
                                    ])
                                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
                            ]),
                        \Filament\Forms\Components\Builder\Block::make('hero')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('title')->required(),
                                \Filament\Forms\Components\TextInput::make('subtitle'),
                                \Filament\Forms\Components\TextInput::make('button_text'),
                                \Filament\Forms\Components\TextInput::make('button_link'),
                                \Filament\Forms\Components\TextInput::make('button_2_text'),
                                \Filament\Forms\Components\TextInput::make('button_2_link'),
                                \Filament\Forms\Components\FileUpload::make('background_image')->image()->directory('website'),
                            ]),
                        \Filament\Forms\Components\Builder\Block::make('about')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('title')->required(),
                                \Filament\Forms\Components\Textarea::make('text')->required(),
                                \Filament\Forms\Components\FileUpload::make('image')->image()->directory('website'),
                            ]),
                        \Filament\Forms\Components\Builder\Block::make('features')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('title')->required(),
                                \Filament\Forms\Components\Repeater::make('items')
                                    ->schema([
                                        \Filament\Forms\Components\TextInput::make('title')->required(),
                                        \Filament\Forms\Components\Textarea::make('text')->required(),
                                        \Filament\Forms\Components\TextInput::make('icon'),
                                    ])
                                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null),
                            ]),
                        \Filament\Forms\Components\Builder\Block::make('menu')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('title')->required()->default('Signature Dishes'),
                                \Filament\Forms\Components\Select::make('source')
                                    ->options([
                                        'manual' => 'Manual Entry',
                                        'database' => 'Pull from Database (Menu Items)',
                                    ])
                                    ->default('manual')
                                    ->reactive(),
                                \Filament\Forms\Components\Repeater::make('items')
                                    ->schema([
                                        \Filament\Forms\Components\TextInput::make('name')->required(),
                                        \Filament\Forms\Components\TextInput::make('description'),
                                        \Filament\Forms\Components\TextInput::make('price')->required(),
                                        \Filament\Forms\Components\FileUpload::make('image')->image()->directory('website'),
                                    ])
                                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                                    ->hidden(fn ($get) => $get('source') === 'database'),
                            ]),
                        \Filament\Forms\Components\Builder\Block::make('contact')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('title')->required(),
                                \Filament\Forms\Components\TextInput::make('address'),
                                \Filament\Forms\Components\TextInput::make('phone'),
                                \Filament\Forms\Components\TextInput::make('email'),
                            ]),
                        \Filament\Forms\Components\Builder\Block::make('footer')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('text')->required(),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed(),
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
        if (! $this->selectedTemplate) {
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
        $content['footer_text'] = '© '.date('Y').' '.$content['business_name'].'. All Rights Reserved.';

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
        $this->isEditing = ! $this->isEditing;
        if ($this->isEditing) {
            $this->builderData = $this->website->content;
        }
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('preview')
                ->label('Preview Website')
                ->color('gray')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn () => 'http://'.tenant('slug').'.'.config('tenancy.preview_domain'), shouldOpenInNewTab: true)
                ->visible(fn () => $this->website !== null),
            Action::make('changeTemplate')
                ->label('Change Template')
                ->color('gray')
                ->icon('heroicon-o-swatch')
                ->action(fn () => $this->browsingTemplates = true)
                ->visible(fn () => $this->website !== null && ! $this->browsingTemplates),
        ];
    }

    protected function getViewData(): array
    {
        return [
            'templates' => WebsiteTemplate::where('is_active', true)->get(),
        ];
    }
}

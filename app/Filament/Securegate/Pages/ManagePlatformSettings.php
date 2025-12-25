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

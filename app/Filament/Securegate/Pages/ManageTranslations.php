<?php

namespace App\Filament\Securegate\Pages;

use App\Models\PlatformSetting;
use BackedEnum;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\File;
use UnitEnum;

class ManageTranslations extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-language';

    protected static string|UnitEnum|null $navigationGroup = 'Platform Settings';

    protected string $view = 'filament.securegate.pages.manage-translations';

    protected static ?string $title = 'Manage Translations';

    public ?array $translationData = [];

    public $selectedLocale = 'en';

    protected function getSchemas(): array
    {
        return ['translationForm'];
    }

    public function mount(): void
    {
        $this->loadLocaleData('en');
    }

    public function loadLocaleData($locale): void
    {
        $this->selectedLocale = $locale;
        $path = base_path("lang/{$locale}.json");

        $translations = [];
        if (File::exists($path)) {
            $translations = json_decode(File::get($path), true) ?? [];
        }

        $this->translationData = [
            'locale' => $locale,
            'translations' => $translations,
        ];
    }

    public function translationForm(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Translation Management')
                    ->description('Select a language and edit its translation keys.')
                    ->schema([
                        Select::make('locale')
                            ->label('Select Language')
                            ->options(function () {
                                $supported = PlatformSetting::current()->getSupportedLanguages();
                                $names = [
                                    'en' => 'English 🇺🇸',
                                    'es' => 'Spanish 🇪🇸',
                                    'fr' => 'French 🇫🇷',
                                    'de' => 'German 🇩🇪',
                                    'ar' => 'Arabic 🇸🇦',
                                ];

                                return array_intersect_key($names, array_flip($supported));
                            })
                            ->live()
                            ->afterStateUpdated(fn ($state) => $this->loadLocaleData($state)),

                        KeyValue::make('translations')
                            ->label('Translations')
                            ->keyLabel('Original Text (English)')
                            ->valueLabel('Translated Text')
                            ->reorderable()
                            ->addActionLabel('Add Translation Key'),
                    ]),
            ])
            ->statePath('translationData');
    }

    public function save(): void
    {
        $data = $this->translationForm->getState();
        $locale = $data['locale'];
        $translations = $data['translations'];

        $path = base_path("lang/{$locale}.json");

        // Ensure directory exists
        if (! File::isDirectory(base_path('lang'))) {
            File::makeDirectory(base_path('lang'), 0755, true);
        }

        File::put($path, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        Notification::make()
            ->success()
            ->title("Translations for {$locale} updated successfully.")
            ->send();
    }
}

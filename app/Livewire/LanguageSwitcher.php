<?php

namespace App\Livewire;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\App;
use Livewire\Component;

use Livewire\Attributes\Computed;
use App\Models\PlatformSetting;

class LanguageSwitcher extends Component
{
    public $direction = 'down';

    #[Computed]
    public function languages()
    {
        $supportedLocales = PlatformSetting::current()->getSupportedLanguages();

        $allLanguages = [
            'en' => ['🇺🇸', 'English'],
            'es' => ['🇪🇸', 'Spanish'],
            'fr' => ['🇫🇷', 'French'],
            'de' => ['🇩🇪', 'German'],
            'ar' => ['🇸🇦', 'Arabic']
        ];

        return array_filter($allLanguages, fn($key) => in_array($key, $supportedLocales), ARRAY_FILTER_USE_KEY);
    }

    public function changeLocale($locale)
    {
        $user = auth()->user();

        // If no user found via default auth, check Filament auth since we might be in a panel
        if (!$user && class_exists('\Filament\Facades\Filament')) {
            $user = \Filament\Facades\Filament::auth()->user();
        }

        if ($user) {
            // Check if user model allows mass assignment of 'locale'
            if (method_exists($user, 'getFillable') && in_array('locale', $user->getFillable())) {
                $user->update(['locale' => $locale]);
            } else {
                session()->put('locale', $locale);
            }
        } else {
            session()->put('locale', $locale);
        }

        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.language-switcher');
    }
}

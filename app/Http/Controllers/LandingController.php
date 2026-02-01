<?php

namespace App\Http\Controllers;

use App\Models\PlatformSetting;

class LandingController extends Controller
{
    protected function getThemeData()
    {
        $platformSettings = PlatformSetting::current();
        $theme = $platformSettings->calendly_theme_settings ?? [];

        return [
            'platformSettings' => $platformSettings,
            'theme' => $theme,
        ];
    }

    public function home()
    {
        return view('pages.home-calendly', $this->getThemeData());
    }

    public function pricing()
    {
        return view('pages.pricing-calendly', $this->getThemeData());
    }

    public function features()
    {
        return view('pages.features-calendly', $this->getThemeData());
    }

    public function solutions()
    {
        return view('pages.solutions-calendly', $this->getThemeData());
    }

    public function resources()
    {
        return view('pages.resources-calendly', $this->getThemeData());
    }
}

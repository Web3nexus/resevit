<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\LandingPage;
use App\Models\NewsletterSubscriber;
use App\Models\PricingFeature;
use App\Models\PricingPlan;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function home()
    {
        // Check if Calendly theme is active
        $platformSettings = \App\Models\PlatformSetting::current();
        $activeTheme = $platformSettings->landing_settings['active_theme'] ?? 'default';

        if ($activeTheme === 'calendly') {
            return app(\App\Http\Controllers\LandingController::class)->home();
        }

        return $this->renderPage('home');
    }

    public function pricing()
    {
        // Check if Calendly theme is active
        $platformSettings = \App\Models\PlatformSetting::current();
        $activeTheme = $platformSettings->landing_settings['active_theme'] ?? 'default';

        if ($activeTheme === 'calendly') {
            return app(\App\Http\Controllers\LandingController::class)->pricing();
        }

        $plans = PricingPlan::where('is_active', true)
            ->with([
                'features' => function ($q) {
                    $q->orderBy('order');
                },
            ])
            ->orderBy('order')
            ->get();

        $faqs = Faq::where('category', 'pricing')
            ->orWhere('category', 'general')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('landing.pricing', compact('plans', 'faqs'));
    }

    public function features()
    {
        return $this->renderPage('features');
    }

    public function integrations()
    {
        return $this->renderPage('integrations');
    }

    public function about()
    {
        return $this->renderPage('about');
    }

    public function contact()
    {
        return view('landing.contact');
    }

    public function faq()
    {
        $faqs = Faq::where('is_active', true)
            ->orderBy('order')
            ->get()
            ->groupBy('category');

        return view('landing.faq', compact('faqs'));
    }

    public function privacy()
    {
        return $this->renderPage('privacy');
    }

    public function terms()
    {
        return $this->renderPage('terms');
    }

    public function gdpr()
    {
        return $this->renderPage('gdpr');
    }

    public function dmca()
    {
        return $this->renderPage('dmca');
    }

    public function resources()
    {
        return $this->renderPage('resources');
    }

    public function resourceShow($slug)
    {
        // For individual resources, we could use a different template later,
        // but for now renderPage handles generic slugs too.
        return $this->renderPage($slug);
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        ContactMessage::create($validated);

        return back()->with('success', 'Your message has been sent successfully!');
    }

    public function subscribeNewsletter(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:newsletter_subscribers,email',
        ]);

        NewsletterSubscriber::create(['email' => $validated['email']]);

        return back()->with('success', 'Thank you for subscribing!');
    }

    protected function renderPage($slug)
    {
        $settings = \App\Models\PlatformSetting::current();
        $theme = $settings->landing_settings['active_theme'] ?? 'default';
        $viewPrefix = $theme === 'modern' ? 'landing.themes.modern.' : 'landing.';
        $layout = $theme === 'modern' ? 'layouts.landing-modern' : 'layouts.landing';

        $page = LandingPage::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (! $page) {
            $legal = $settings->legal_settings ?? [];
            $legalMapping = [
                'terms' => 'terms_of_service',
                'privacy' => 'privacy_policy',
                'cookie-policy' => 'cookie_policy',
                'gdpr' => 'gdpr',
                'dmca' => 'dmca',
            ];

            if (isset($legalMapping[$slug]) && ! empty($legal[$legalMapping[$slug]])) {
                return view('landing.legal-simple', [
                    'title' => ucwords(str_replace(['-', '_'], ' ', $slug)),
                    'content' => $legal[$legalMapping[$slug]],
                    'layout' => $layout,
                ]);
            }

            if (view()->exists($viewPrefix.$slug)) {
                return view($viewPrefix.$slug, compact('layout', 'theme'));
            }

            abort(404);
        }

        $sections = $page->sections()
            ->where('is_active', true)
            ->with('items')
            ->get();

        $testimonials = null;
        if ($sections->contains('type', 'testimonials')) {
            $testimonials = Testimonial::where('is_active', true)
                ->orderBy('order')
                ->get();
        }

        $plans = null;
        if ($sections->contains('type', 'pricing')) {
            $plans = PricingPlan::where('is_active', true)
                ->with('features')
                ->orderBy('order')
                ->get();
        }

        $pricing_features = null;
        if ($sections->contains('type', 'features')) {
            $pricing_features = PricingFeature::where('is_active', true)
                ->orderBy('order')
                ->get();
        }

        $viewName = view()->exists($viewPrefix.'dynamic') ? $viewPrefix.'dynamic' : 'landing.dynamic';

        return view($viewName, compact('page', 'sections', 'testimonials', 'plans', 'pricing_features', 'layout', 'theme'));
    }
}

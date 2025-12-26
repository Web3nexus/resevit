<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\LandingPage;
use App\Models\LandingSection;
use App\Models\Testimonial;
use App\Models\PricingPlan;
use App\Models\Faq;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use App\Models\PricingFeature;

class LandingPageController extends Controller
{
    public function home()
    {
        return $this->renderPage('home');
    }

    public function pricing()
    {
        $plans = PricingPlan::where('is_active', true)
            ->with([
                'features' => function ($q) {
                    $q->orderBy('order');
                }
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
        $page = LandingPage::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (!$page) {
            // Check if it's a legal page that we have in PlatformSettings
            $settings = \App\Models\PlatformSetting::current();
            $legal = $settings->legal_settings ?? [];

            $legalMapping = [
                'terms' => 'terms_of_service',
                'privacy' => 'privacy_policy',
                'cookie-policy' => 'cookie_policy',
                'gdpr' => 'gdpr',
            ];

            if (isset($legalMapping[$slug]) && !empty($legal[$legalMapping[$slug]])) {
                return view('landing.legal-simple', [
                    'title' => ucwords(str_replace('-', ' ', $slug)),
                    'content' => $legal[$legalMapping[$slug]],
                ]);
            }

            // Fallback to a static view if no DB entry exists, or 404
            if (view()->exists("landing.{$slug}")) {
                return view("landing.{$slug}");
            }

            // Only abort if NEITHER db record NOR static view exists
            abort(404);
        }

        $sections = $page->sections()
            ->where('is_active', true)
            ->with('items')
            ->get();

        // Inject global data for specific section types
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

        return view('landing.dynamic', compact('page', 'sections', 'testimonials', 'plans', 'pricing_features'));
    }
}

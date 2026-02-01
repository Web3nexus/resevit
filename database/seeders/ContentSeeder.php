<?php

namespace Database\Seeders;

use App\Models\PlatformSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Documentation Articles
        $articles = [
            [
                'title' => 'Getting Started with Resevit',
                'slug' => 'getting-started-with-resevit',
                'category' => 'Getting Started',
                'excerpt' => 'Your first steps to digital transformation.',
                'content' => '<h1>Welcome to Resevit</h1><p>We are thrilled to have you on board. This guide will walk you through the initial setup process, from creating your restaurant profile to taking your first reservation.</p><h3>1. Complete Your Profile</h3><p>Head to the settings page and fill in your restaurant details. The more info you provide (photos, descriptions, amenities), the more attractive your listing will be to diners.</p><h3>2. Set Up Your Floor Plan</h3><p>Our drag-and-drop builder allows you to recreate your restaurant\'s layout. Add tables, define capacities, and even mark "best seats".</p><h3>3. Connect Your Socials</h3><p>Integrate your Instagram and Facebook pages to allow streamlined bookings directly from your social media profiles.</p><h3>4. Invite Your Team</h3><p>Add your staff members and assign them roles (Manager, Host, Server). They will receive an email to set up their own accounts.</p>',
                'order' => 1,
                'is_published' => true,
            ],
            [
                'title' => 'Managing Reservations',
                'slug' => 'managing-reservations',
                'category' => 'Operations',
                'excerpt' => 'How to handle bookings, cancellations, and walk-ins.',
                'content' => '<h1>Mastering Your Bookings</h1><p>Resevit centralizes all your reservations into one intuitive dashboard. Here is how to stay on top of it.</p><h3>The Calendar View</h3><p>See your entire week or month at a glance. Color-coded blocks indicate busy periods, special events, or closures.</p><h3>Handling Walk-Ins</h3><p>Quickly add walk-in guests to the system. Assign them "phantom" tables if waiting, or seat them immediately if space permits.</p><h3>Cancellations & No-Shows</h3><p>Mark a reservation as cancelled with a reason code. If a guest is a no-show, tag them; our system tracks repeat offenders and can auto-flag them for future bookings.</p>',
                'order' => 2,
                'is_published' => true,
            ],
            [
                'title' => 'Understanding Analytics',
                'slug' => 'understanding-analytics',
                'category' => 'Reporting',
                'excerpt' => 'Decode your data to make smarter business decisions.',
                'content' => '<h1>Data-Driven Success</h1><p>Stop guessing and start knowing. Our analytics suite gives you deep insights into your restaurant\'s performance.</p><h3>Sales vs. Covers</h3><p>Track your revenue per cover. Identify high-spending shifts and underperforming days.</p><h3>Server Performance</h3><p>See which staff members turn tables fastest, who has the highest average ticket size, and who gets the best reviews.</p><h3>Menu Analysis</h3><p> (Integration required) Understand which dishes drive return visits and which are costing you money.</p>',
                'order' => 3,
                'is_published' => true,
            ],
            [
                'title' => 'API Integration Guide',
                'slug' => 'api-integration-guide',
                'category' => 'Developers',
                'excerpt' => 'Technical documentation for connecting tools.',
                'content' => '<h1>Resevit API Access</h1><p>For advanced users and developers, we offer a RESTful API to connect Resevit with your other tools.</p><h3>Authentication</h3><p>We use Bearer Token authentication. You can generate API keys in your developer settings.</p><h3>Endpoints</h3><p>Our API covers Reservations, Customers, and Menu Management. See the full Redoc reference for request/response examples.</p><h3>Rate Limits</h3><p>To ensure stability, we limit requests to 60 per minute per IP. Contact support if you need higher limits for enterprise use cases.</p>',
                'order' => 4,
                'is_published' => true,
            ],
            [
                'title' => 'Pricing & Plans Explained',
                'slug' => 'pricing-plans-explained',
                'category' => 'Billing',
                'excerpt' => 'Everything you need to know about our transparent pricing.',
                'content' => '<h1>Transparent Pricing for Every Stage</h1><p>We believe in simple, transparent pricing that grows with your business. No hidden fees, no \'gotchas\'—just pure value.</p><h3>The Essentials Plan</h3><p>Perfect for single-location bistros and cafes. You get full access to our reservation system, basic analytics, and the floor plan builder.</p><h3>The Pro Plan</h3><p>Designed for high-volume restaurants. Includes AI insights, staff scheduling tools, and enhanced marketing features.</p><h3>Enterprise & Multi-Chain</h3><p>For restaurant groups and franchises. Offers cross-location reporting, custom integrations, and priority support.</p>',
                'order' => 5,
                'is_published' => true,
            ],
            [
                'title' => 'Multi-Location Management',
                'slug' => 'multi-location-management',
                'category' => 'Management',
                'excerpt' => 'Managing an empire? Here is how to handle multiple branches.',
                'content' => '<h1>Scaling with Resevit</h1><p>As you expand from one location to many, Resevit scales with you.</p><h3>The Unified Dashboard</h3><p>Switch between locations with a single click. Compare performance metrics across different cities.</p><h3>Centralized Menu Control</h3><p>Push menu updates to all locations simultaneously, or tailor them to local tastes.</p>',
                'order' => 6,
                'is_published' => true,
            ],
            [
                'title' => 'Staff Scheduling & Payroll',
                'slug' => 'staff-scheduling-payroll',
                'category' => 'Operations',
                'excerpt' => 'Keep your team happy and your labor costs optimized.',
                'content' => '<h1>Effective Team Management</h1><p>A happy team is the backbone of a great restaurant.</p><h3>Drag-and-Drop Scheduling</h3><p>Build your weekly roster in minutes. The system flags overtime risks and staffing gaps.</p><h3>Time & Attendance</h3><p>Our digital clock-in system ensures accurate time tracking.</p>',
                'order' => 7,
                'is_published' => true,
            ],
            [
                'title' => 'Marketing & Growth Tools',
                'slug' => 'marketing-growth-tools',
                'category' => 'Growth',
                'excerpt' => 'Turn one-time diners into lifelong regulars.',
                'content' => '<h1>Growing Your Brand</h1><p>Resevit goes beyond management; we help you attract and retain more customers.</p><h3>Automated Email Campaigns</h3><p>Send personalized offers based on dining history.</p><h3>Promotion Management</h3><p>Create time-sensitive deals (like Happy Hours).</p>',
                'order' => 8,
                'is_published' => true,
            ],
            [
                'title' => 'Secure Payments with Stripe',
                'slug' => 'secure-payments-with-stripe',
                'category' => 'Security',
                'excerpt' => 'How we protect your financial data and handle transactions.',
                'content' => '<h1>Financial Security at the Core</h1><p>We\'ve partnered with Stripe, the global leader in payments.</p><h3>PCI-DSS Compliance</h3><p>Resevit never touches your customer\'s raw credit card data.</p><h3>Handling Deposits & No-Shows</h3><p>Reduce \'no-shows\' by requiring a small deposit.</p>',
                'order' => 9,
                'is_published' => true,
            ],
            [
                'title' => 'Customer Loyalty & Reviews',
                'slug' => 'customer-loyalty-reviews',
                'category' => 'Operations',
                'excerpt' => 'Harness the power of feedback and build a loyal community.',
                'content' => '<h1>Building Relationships</h1><p>In the restaurant business, reputation is everything.</p><h3>The Review Dashboard</h3><p>Aggregate reviews from multiple platforms into one inbox.</p><h3>Loyalty Points System</h3><p>Reward repeat visits automatically.</p>',
                'order' => 10,
                'is_published' => true,
            ],
        ];

        foreach ($articles as $art) {
            DB::table('documentation_articles')->updateOrInsert(
                ['slug' => $art['slug']],
                array_merge($art, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        // 2. Platform Settings (Restore Footer Links)
        // Ensure the table expects 'footer_settings' JSON column
        $settings = PlatformSetting::first();
        if ($settings) {
            $settings->footer_settings = [
                'others' => [
                    ['url' => '/features', 'label' => 'Features', 'is_visible' => true],
                    ['url' => '/status', 'label' => 'System Status', 'is_visible' => true],
                    ['url' => '/login', 'label' => 'Log In', 'is_visible' => true],
                    ['url' => '/docs', 'label' => 'Documentation', 'is_visible' => true],
                ],
            ];

            // Restore Legal Settings (Content for modals/pages)
            $settings->legal_settings = [
                'terms_of_service' => '<p>Terms content...</p>', // Simplified, actual content is in pages
                'privacy_policy' => '<p>Privacy content...</p>',
                'cookie_policy' => '<p>Cookie content...</p>',
                'gdpr' => '<p>GDPR content...</p>',
            ];

            $settings->save();
        }

        // 3. Legal Documents (Required for Footer)
        $legalDocs = [
            [
                'title' => 'Terms of Service',
                'slug' => 'terms-of-service',
                'content' => '<h1>Terms of Service</h1><p>Welcome to Resevit. By accessing or using our platform, you agree to be bound by these terms. We\'ve tried to keep them straightforward because we know nobody likes reading pages of legalese.</p><h3>1. Using Our Platform</h3><p>Resevit provides a suite of tools for restaurant management. You agree to use these tools for their intended purpose and not to engage in any activity that might harm the platform or its users. This includes attempts to reverse-engineer our software or disrupt our services.</p><h3>2. Restaurant Responsibilities</h3><p>As a restaurant owner, you are responsible for the accuracy of your listings, menus, and available slots. Resevit is a facilitator, but the ultimate dining experience is between you and your customers.</p><h3>3. Booking & Cancellations</h3><p>Our platform enables real-time bookings. You agree to honor reservations made through the system or communicate promptly with customers if changes are necessary. Misuse of the booking system may lead to account suspension.</p><h3>4. Payments & Fees</h3><p>Subscribed businesses agree to pay all applicable fees according to their chosen plan. Fees are non-refundable unless otherwise specified. We use secure third-party processors like Stripe to handle your financial data.</p><h3>5. Limitation of Liability</h3><p>While we strive for 100% uptime and a bug-free experience, Resevit is provided \'as-is\'. We are not liable for indirect damages or lost profits resulting from the use of our service.</p><h3>6. Updates to Terms</h3><p>We might update these terms from time to time as our platform grows. We\'ll notify you of any significant changes via email or dashboard notifications.</p>',
                'order' => 1,
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '<h1>Privacy Policy</h1><p>At Resevit, we take your privacy seriously. We\'re not in the business of selling your data; we\'re in the business of helping your restaurant thrive.</p><h3>What Data We Collect</h3><p>We collect information necessary to run the service, including restaurant details, staff schedules, and reservation data (like diner names and contact info). We also collect technical data like IP addresses and browser types to help us optimize performance.</p><h3>How We Use Your Data</h3><p>Your data is used solely to provide and improve the Resevit experience. This includes facilitating bookings, generating analytics, and sending system-related notifications. We do not share your sensitive business data with third parties for marketing purposes.</p><h3>Data Security</h3><p>We use industry-standard encryption and security protocols to keep your information safe. Our database is monitored 24/7 to prevent unauthorized access.</p><h3>Your Choices</h3><p>You can view, edit, or delete your account information at any time through your dashboard. If you have questions about your data, our team is always here to help.</p>',
                'order' => 2,
            ],
            [
                'title' => 'GDPR Compliance',
                'slug' => 'gdpr-compliance',
                'content' => '<h1>GDPR Compliance</h1><p>Resevit is fully committed to the principles of the General Data Protection Regulation (GDPR). We believe transparency and user control are fundamental rights.</p><h3>Data Subject Rights</h3><p>If you are based in the EU, you have specific rights under GDPR, including the right to access, rectify, or erase your personal data. You can exercise these rights directly through your account settings or by contacting our Data Protection Officer.</p><h3>Data Processor Agreement</h3><p>For restaurants using Resevit to process diner data, we act as a Data Processor. Our terms include standard contractual clauses to ensure your customers\' data is handled with the highest level of care.</p><h3>Data Archiving</h3><p>We only keep personal data for as long as it\'s needed to fulfill the purposes for which it was collected. Inactive accounts are periodically reviewed for data minimization.</p>',
                'order' => 3,
            ],
            [
                'title' => 'DMCA Policy',
                'slug' => 'dmca-policy',
                'content' => '<h1>DMCA & Copyright Policy</h1><p>Resevit respects the intellectual property of others. If you believe that your copyrighted work has been used on our platform in a way that constitutes infringement, please let us know.</p><h3>Reporting Infringement</h3><p>To file a notice, please provide our designated agent with a description of the copyrighted work and where it is located on our site. Include your contact information and a statement of good faith belief that the use is not authorized.</p><h3>Counter-Notifications</h3><p>If you believe your content was removed by mistake, you can submit a counter-notice. We will review all claims fairly and promptly.</p><h3>Repeat Infringers</h3><p>It is our policy to terminate the accounts of users who repeatedly infringe on the copyrights of others.</p>',
                'order' => 4,
            ],
        ];

        foreach ($legalDocs as $doc) {
            \App\Models\LegalDocument::updateOrCreate(
                ['slug' => $doc['slug']],
                array_merge($doc, ['is_published' => true])
            );
        }
    }
}

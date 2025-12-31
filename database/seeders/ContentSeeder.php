<?php

namespace Database\Seeders;

use App\Models\LegalDocument;
use App\Models\Faq;
use App\Models\DocumentationArticle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedLegalDocuments();
        $this->seedFaqs();
        $this->seedDocumentation();
    }

    private function seedLegalDocuments(): void
    {
        $documents = [
            [
                'title' => 'Terms of Service',
                'content' => "<h1>Terms of Service</h1>
                <p>Welcome to Resevit. By accessing or using our platform, you agree to be bound by these terms. We've tried to keep them straightforward because we know nobody likes reading pages of legalese.</p>
                
                <h3>1. Using Our Platform</h3>
                <p>Resevit provides a suite of tools for restaurant management. You agree to use these tools for their intended purpose and not to engage in any activity that might harm the platform or its users. This includes attempts to reverse-engineer our software or disrupt our services.</p>
                
                <h3>2. Restaurant Responsibilities</h3>
                <p>As a restaurant owner, you are responsible for the accuracy of your listings, menus, and available slots. Resevit is a facilitator, but the ultimate dining experience is between you and your customers.</p>
                
                <h3>3. Booking & Cancellations</h3>
                <p>Our platform enables real-time bookings. You agree to honor reservations made through the system or communicate promptly with customers if changes are necessary. Misuse of the booking system may lead to account suspension.</p>
                
                <h3>4. Payments & Fees</h3>
                <p>Subscribed businesses agree to pay all applicable fees according to their chosen plan. Fees are non-refundable unless otherwise specified. We use secure third-party processors like Stripe to handle your financial data.</p>
                
                <h3>5. Limitation of Liability</h3>
                <p>While we strive for 100% uptime and a bug-free experience, Resevit is provided 'as-is'. We are not liable for indirect damages or lost profits resulting from the use of our service.</p>
                
                <h3>6. Updates to Terms</h3>
                <p>We might update these terms from time to time as our platform grows. We'll notify you of any significant changes via email or dashboard notifications.</p>",
                'order' => 1,
            ],
            [
                'title' => 'Privacy Policy',
                'content' => "<h1>Privacy Policy</h1>
                <p>At Resevit, we take your privacy seriously. We're not in the business of selling your data; we're in the business of helping your restaurant thrive.</p>
                
                <h3>What Data We Collect</h3>
                <p>We collect information necessary to run the service, including restaurant details, staff schedules, and reservation data (like diner names and contact info). We also collect technical data like IP addresses and browser types to help us optimize performance.</p>
                
                <h3>How We Use Your Data</h3>
                <p>Your data is used solely to provide and improve the Resevit experience. This includes facilitating bookings, generating analytics, and sending system-related notifications. We do not share your sensitive business data with third parties for marketing purposes.</p>
                
                <h3>Data Security</h3>
                <p>We use industry-standard encryption and security protocols to keep your information safe. Our database is monitored 24/7 to prevent unauthorized access.</p>
                
                <h3>Your Choices</h3>
                <p>You can view, edit, or delete your account information at any time through your dashboard. If you have questions about your data, our team is always here to help.</p>",
                'order' => 2,
            ],
            [
                'title' => 'GDPR Compliance',
                'content' => "<h1>GDPR Compliance</h1>
                <p>Resevit is fully committed to the principles of the General Data Protection Regulation (GDPR). We believe transparency and user control are fundamental rights.</p>
                
                <h3>Data Subject Rights</h3>
                <p>If you are based in the EU, you have specific rights under GDPR, including the right to access, rectify, or erase your personal data. You can exercise these rights directly through your account settings or by contacting our Data Protection Officer.</p>
                
                <h3>Data Processor Agreement</h3>
                <p>For restaurants using Resevit to process diner data, we act as a Data Processor. Our terms include standard contractual clauses to ensure your customers' data is handled with the highest level of care.</p>
                
                <h3>Data Archiving</h3>
                <p>We only keep personal data for as long as it's needed to fulfill the purposes for which it was collected. Inactive accounts are periodically reviewed for data minimization.</p>",
                'order' => 3,
            ],
            [
                'title' => 'DMCA Policy',
                'content' => "<h1>DMCA & Copyright Policy</h1>
                <p>Resevit respects the intellectual property of others. If you believe that your copyrighted work has been used on our platform in a way that constitutes infringement, please let us know.</p>
                
                <h3>Reporting Infringement</h3>
                <p>To file a notice, please provide our designated agent with a description of the copyrighted work and where it is located on our site. Include your contact information and a statement of good faith belief that the use is not authorized.</p>
                
                <h3>Counter-Notifications</h3>
                <p>If you believe your content was removed by mistake, you can submit a counter-notice. We will review all claims fairly and promptly.</p>
                
                <h3>Repeat Infringers</h3>
                <p>It is our policy to terminate the accounts of users who repeatedly infringe on the copyrights of others.</p>",
                'order' => 4,
            ],
        ];

        foreach ($documents as $doc) {
            LegalDocument::updateOrCreate(
                ['slug' => Str::slug($doc['title'])],
                [
                    'title' => $doc['title'],
                    'content' => $doc['content'],
                    'order' => $doc['order'],
                    'is_published' => true,
                ]
            );
        }
    }

    private function seedFaqs(): void
    {
        $faqs = [
            [
                'question' => "What exactly is Resevit?",
                'answer' => "Resevit is an all-in-one management suite designed specifically for modern restaurants. We combine reservation management, staff scheduling, real-time analytics, and AI-driven growth insights into a single, sleek application. Think of us as your restaurant's digital operating system.",
                'category' => 'General',
                'order' => 1,
            ],
            [
                'question' => "Is it hard to set up my restaurant on the platform?",
                'answer' => "Not at all! We've designed our onboarding process to be as smooth as possible. You can import your current menu, set your floor plan, and start taking bookings in under 30 minutes. Our team is also available to walk you through every step if you need a hand.",
                'category' => 'Getting Started',
                'order' => 2,
            ],
            [
                'question' => "How do my customers book a table?",
                'answer' => "Customers can book through your dedicated Resevit landing page or via a widget you can easily embed on your own website. The process takes less than a minute, and they'll receive instant confirmations via email or SMS.",
                'category' => 'Reservations',
                'order' => 3,
            ],
            [
                'question' => "Can I manage more than one location?",
                'answer' => "Yes! Resevit is built for growth. Whether you have a single bistro or a nationwide chain, you can manage all your branches from a central dashboard with role-based access for your managers.",
                'category' => 'Management',
                'order' => 4,
            ],
            [
                'question' => "What kind of 'AI Insights' do you provide?",
                'answer' => "Our AI analyzes your historical data to predict busy periods, helping you optimize staff levels and reduce labor costs. It also identifies your most popular menu items and helps you understand customer sentiment through automated review analysis.",
                'category' => 'Features',
                'order' => 5,
            ],
            [
                'question' => "Is my data safe and secure?",
                'answer' => "Absolutely. We use enterprise-grade encryption (SSL/TLS) for all data transfers and store your information in secure, redundantly-backed servers. We never store credit card numbers on our servers; all payments are handled by Stripe, a global leader in payment security.",
                'category' => 'Security',
                'order' => 6,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                [
                    'answer' => $faq['answer'],
                    'category' => $faq['category'],
                    'order' => $faq['order'],
                    'is_active' => true,
                ]
            );
        }
    }

    private function seedDocumentation(): void
    {
        $articles = [
            [
                'title' => 'Getting Started with Resevit',
                'category' => 'Basics',
                'excerpt' => 'Welcome to the platform! Here is how to get your restaurant up and running in no time.',
                'content' => "<h1>Welcome to Resevit</h1>
                <p>We're thrilled to have you on board. This guide will walk you through the essential steps to configure your account and start welcoming diners.</p>
                
                <h3>1. Complete Your Profile</h3>
                <p>Head over to the 'Business Settings' section. Here, you'll upload your logo, set your contact details, and define your operating hours. This information appears on your public booking page, so make it look great!</p>
                
                <h3>2. Design Your Floor Plan</h3>
                <p>Use our interactive Floor Plan Builder to recreate your restaurant layout. Add tables, define their seating capacity, and mark 'zones' for easier management. This allows the system to auto-assign tables based on group size.</p>
                
                <h3>3. Add Your Menu</h3>
                <p>Upload your dishes, categorize them (Starters, Mains, Drinks, etc.), and add tempting descriptions. Our platform supports variants and addons, allowing customers to customize their orders if you offer pickup or pre-ordering features.</p>
                
                <h3>4. Invite Your Team</h3>
                <p>Add your staff members and assign them roles (Admin, Manager, Waitstaff). Each role has tailored permissions to ensure everyone has access to the tools they need without overwhelming them.</p>
                
                <p>Ready to go? If you get stuck, click the 'Help' icon at the bottom of the screen for instant support.</p>",
                'order' => 1,
            ],
            [
                'title' => 'Mastering Reservations',
                'category' => 'Daily Operations',
                'excerpt' => 'A deep dive into managing bookings, walk-ins, and waitlists.',
                'content' => "<h1>Reservation Management</h1>
                <p>Keeping your tables full is at the heart of what we do. Here is how to manage your bookings like a pro.</p>
                
                <h3>The Live Monitor</h3>
                <p>Your dashboard features a real-time 'Live Monitor' that shows current table statuses. You can see which tables are occupied, which are reserved for later, and which are nearing the end of their meal.</p>
                
                <h3>Handling Walk-ins</h3>
                <p>Don't turn customers away! Simply click any available table on your floor plan to create a walk-in entry. The system will track their duration and update your availability accordingly.</p>
                
                <h3>Waitlist Management</h3>
                <p>On busy nights, use the Waitlist feature to keep customers happy. Enter their phone number, and the system will automatically text them when their table is ready.</p>
                
                <h3>Booking Rules</h3>
                <p>In your settings, you can define maximum group sizes, booking lead times, and turnover durations. These rules ensure you never overbook or leave a table empty for too long.</p>",
                'order' => 2,
            ],
            [
                'title' => 'Understanding Your Analytics',
                'category' => 'Growth',
                'excerpt' => 'How to use Resevit data to increase your restaurant revenue.',
                'content' => "<h1>Analytics & Insights</h1>
                <p>Data-driven decisions are the secret to a successful restaurant. Resevit gives you the numbers you need to grow.</p>
                
                <h3>Sales Performance</h3>
                <p>See your daily, weekly, and monthly revenue trends. Compare performance across different shifts or locations to see what's working best.</p>
                
                <h3>Customer Loyalty</h3>
                <p>Identify your most frequent diners. Resevit automatically tracks visitation patterns, allowing you to reward your 'regulars' with special offers or personalized greetings.</p>
                
                <h3>Staff Efficiency</h3>
                <p>Our analytics show you the average turnover time per table and per server. Use this data to provide better training or optimize your staffing levels during peak hours.</p>
                
                <h3>AI Predictions</h3>
                <p>Keep an eye on the 'Predictive Insights' widget. It uses machine learning to forecast demand, allowing you to order supplies and schedule staff with pinpoint accuracy.</p>",
                'order' => 3,
            ],
            [
                'title' => 'How Resevit AI Works',
                'category' => 'Advanced Features',
                'excerpt' => 'A peek under the hood of our neural engine and how it helps your restaurant.',
                'content' => "<h1>The Power of Resevit AI</h1>
                <p>Resevit isn't just a digital ledger; it's an intelligent partner. Our AI engine works silently in the background to turn your raw data into actionable growth strategies.</p>
                
                <h3>Neural Demand Forecasting</h3>
                <p>By analyzing years of dining trends, local events, and weather patterns, our AI predicts your upcoming guest volume with remarkable accuracy. This means you can schedule the perfect number of staff—never overstaffed on slow nights, and never overwhelmed on busy ones.</p>
                
                <h3>Smart Table Optimization</h3>
                <p>The AI suggests the most efficient seating arrangements during your peak hours. It calculates 'turn-time' based on specific party sizes and menu choices, ensuring you maximize revenue per square foot without rushing your guests.</p>
                
                <h3>Automated Sentiment Analysis</h3>
                <p>Our machine learning models scan reviews and customer feedback to identify recurring themes. Whether it's a dish that's consistently praised or a service bottleneck on Saturday nights, the AI highlights what needs your attention.</p>",
                'order' => 4,
            ],
            [
                'title' => 'Pricing & Plans Explained',
                'category' => 'Billing',
                'excerpt' => 'Everything you need to know about our transparent pricing structure.',
                'content' => "<h1>Transparent Pricing for Every Stage</h1>
                <p>We believe in simple, transparent pricing that grows with your business. No hidden fees, no 'gotchas'—just pure value.</p>
                
                <h3>The Essentials Plan</h3>
                <p>Perfect for single-location bistros and cafes. You get full access to our reservation system, basic analytics, and the floor plan builder. It's everything you need to digitize your operations.</p>
                
                <h3>The Pro Plan</h3>
                <p>Designed for high-volume restaurants. This includes advanced AI insights, staff scheduling tools, and enhanced marketing features like automated email campaigns and SMS waitlists.</p>
                
                <h3>Enterprise & Multi-Chain</h3>
                <p>For restaurant groups and franchises. This plan offers cross-location reporting, custom integrations, a dedicated account manager, and priority 24/7 support.</p>
                
                <h3>Transaction Fees</h3>
                <p>For premium features like pre-payments or cancellations protection, we pass through industry-standard processing fees from Stripe. We keep our margins low so you keep more of your revenue.</p>",
                'order' => 5,
            ],
            [
                'title' => 'Multi-Location Management',
                'category' => 'Management',
                'excerpt' => 'Managing an empire? Here is how to handle multiple branches effortlessly.',
                'content' => "<h1>Scaling with Resevit</h1>
                <p>As you expand from one location to many, Resevit scales with you. Our multi-tenant architecture ensures each branch is unique while keeping you in total control.</p>
                
                <h3>The Unified Dashboard</h3>
                <p>Switch between locations with a single click. Compare performance metrics across different cities or neighborhoods to identify your 'star' performers and standardizing operations.</p>
                
                <h3>Centralized Menu Control</h3>
                <p>Push menu updates to all locations simultaneously, or tailor them to local tastes. You can manage pricing and availability globally while allowing branch managers to make local adjustments.</p>
                
                <h3>Global Staffing</h3>
                <p>Moving staff between branches? Resevit handles it. Track hours and performance globally, ensuring your best people are where they're needed most.</p>",
                'order' => 6,
            ],
            [
                'title' => 'Staff Scheduling & Payroll',
                'category' => 'Operations',
                'excerpt' => 'Keep your team happy and your labor costs optimized.',
                'content' => "<h1>Effective Team Management</h1>
                <p>A happy team is the backbone of a great restaurant. Our scheduling tools are built to respect your staff's time while hitting your labor targets.</p>
                
                <h3>Drag-and-Drop Scheduling</h3>
                <p>Build your weekly roster in minutes. The system flags overtime risks and staffing gaps based on AI-predicted busy periods. Once published, staff get instant notifications on their devices.</p>
                
                <h3>Time & Attendance</h3>
                <p>Our digital clock-in system ensures accurate time tracking. Staff can clock in/out via the terminal or their mobile devices (with geofencing), making payroll a breeze.</p>
                
                <h3>Shift Swaps & Requests</h3>
                <p>Empower your team to manage their own schedules. Staff can request swaps or time off directly through the app, requiring only a quick approval from a manager.</p>",
                'order' => 7,
            ],
            [
                'title' => 'Marketing & Growth Tools',
                'category' => 'Growth',
                'excerpt' => 'Turn one-time diners into lifelong regulars.',
                'content' => "<h1>Growing Your Brand</h1>
                <p>Resevit goes beyond management; we help you attract and retain more customers through built-in marketing tools.</p>
                
                <h3>Automated Email Campaigns</h3>
                <p>Send personalized offers based on dining history. Automatically reach out to customers who haven't visited in a while or celebrate their birthdays with a special treat.</p>
                
                <h3>Promotion Management</h3>
                <p>Create time-sensitive deals (like Happy Hours or Early Bird specials) that update automatically on your booking page. Track the ROI of every campaign in real-time.</p>
                
                <h3>Influencer Management</h3>
                <p>Track the impact of your social media partnerships. Generate unique booking links for influencers and measure exactly how many covers they bring to your door.</p>",
                'order' => 8,
            ],
            [
                'title' => 'Secure Payments with Stripe',
                'category' => 'Security',
                'excerpt' => 'How we protect your financial data and handle transactions.',
                'content' => "<h1>Financial Security at the Core</h1>
                <p>We've partnered with Stripe, the global leader in payments, to ensure your transactions are as secure as they are seamless.</p>
                
                <h3>PCI-DSS Compliance</h3>
                <p>Resevit never touches your customer's raw credit card data. Everything is tokenized and processed through Stripe's secure infrastructure, meeting the highest security standards in the industry.</p>
                
                <h3>Handling Deposits & No-Shows</h3>
                <p>Reduce 'no-shows' by requiring a small deposit for peak times or large groups. If a guest cancels last minute, the system handles the refund or fee according to your specific policy.</p>
                
                <h3>Fast Payouts</h3>
                <p>Get your money faster. Our integration ensures that funds from pre-payments and deposits are settled into your bank account with minimal delay.</p>",
                'order' => 9,
            ],
            [
                'title' => 'Customer Loyalty & Reviews',
                'category' => 'Operations',
                'excerpt' => 'Harness the power of feedback and build a loyal community.',
                'content' => "<h1>Building Relationships</h1>
                <p>In the restaurant business, reputation is everything. Resevit helps you manage your online presence and reward your biggest fans.</p>
                
                <h3>The Review Dashboard</h3>
                <p>Aggregate reviews from multiple platforms into one inbox. Respond promptly to feedback and show your customers that their opinion matters.</p>
                
                <h3>Loyalty Points System</h3>
                <p>Reward repeat visits automatically. Our built-in loyalty system tracks points based on spend or visits, which customers can redeem for discounts or exclusive experiences.</p>
                
                <h3>Guest Profiles</h3>
                <p>Know your guests before they walk in. View dietary preferences, seat favorites, and past orders to provide a truly personalized service that keeps them coming back.</p>",
                'order' => 10,
            ],
        ];

        foreach ($articles as $article) {
            DocumentationArticle::updateOrCreate(
                ['slug' => Str::slug($article['title'])],
                [
                    'title' => $article['title'],
                    'category' => $article['category'],
                    'excerpt' => $article['excerpt'],
                    'content' => $article['content'],
                    'order' => $article['order'],
                    'is_published' => true,
                ]
            );
        }
    }
}

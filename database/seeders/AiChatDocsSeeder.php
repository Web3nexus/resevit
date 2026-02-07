<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AiChatDocsSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Social Media Connections',
                'slug' => 'social-media-connections',
                'category' => 'Integrations',
                'excerpt' => 'Connect your WhatsApp, Facebook, and Instagram accounts to Resevit.',
                'content' => '<h1>Social Media Connections</h1><p>Resevit allows you to sync conversations from your favorite social media platforms directly into your dashboard.</p><h3>Supported Platforms</h3><ul><li><strong>WhatsApp</strong>: Link your WhatsApp Business API account.</li><li><strong>Facebook Messenger</strong>: Connect your Facebook Page.</li><li><strong>Instagram DM</strong>: Link your Instagram Professional account.</li></ul><h3>Setup Process</h3><ol><li>Navigate to <strong>Settings > Connections</strong>.</li><li>Click <strong>Connect</strong> for your desired platform.</li><li>Authorize Resevit via the secure OAuth popup.</li></ol>',
                'order' => 11,
                'is_published' => true,
            ],
            [
                'title' => 'AI Chat Assistant',
                'slug' => 'ai-chat-assistant',
                'category' => 'Growth',
                'excerpt' => 'Let AI handle your customer inquiries and bookings.',
                'content' => '<h1>AI Chat Assistant</h1><p>Our powerful AI can take charge of your social media chats, answering common questions and even taking reservations.</p><h3>Key Features</h3><ul><li><strong>24/7 Availability</strong>: AI never sleeps!</li><li><strong>Automated Bookings</strong>: Customers can book tables directly through chat.</li><li><strong>Manual Takeover</strong>: Reply anytime; the AI will learn from your manual responses.</li></ul><h3>Enabling the AI</h3><ol><li>Go to <strong>Marketing > Chat Automation</strong>.</li><li>Create a new flow with trigger <strong>AI Assistant</strong>.</li><li>Make sure you have configured your AI API keys in the system settings.</li></ol>',
                'order' => 12,
                'is_published' => true,
            ],
        ];

        foreach ($articles as $art) {
            DB::table('documentation_articles')->updateOrInsert(
                ['slug' => $art['slug']],
                array_merge($art, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}

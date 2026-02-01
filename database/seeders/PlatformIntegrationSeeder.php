<?php

namespace Database\Seeders;

use App\Models\Integration;
use Illuminate\Database\Seeder;

class PlatformIntegrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $integrations = [
            [
                'name' => 'Stripe',
                'description' => 'Accept credit cards and other payments.',
                'url' => 'https://stripe.com',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name' => 'Calendly',
                'description' => 'Schedule meetings and appointments.',
                'url' => 'https://calendly.com',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'Google Analytics',
                'description' => 'Track and report website traffic.',
                'url' => 'https://analytics.google.com',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'name' => 'Mailchimp',
                'description' => 'Email marketing and automation.',
                'url' => 'https://mailchimp.com',
                'is_active' => true,
                'order' => 4,
            ],
        ];

        foreach ($integrations as $integration) {
            Integration::updateOrCreate(
                ['name' => $integration['name']],
                $integration
            );
        }

        $this->command->info('Platform integrations seeded successfully.');
    }
}

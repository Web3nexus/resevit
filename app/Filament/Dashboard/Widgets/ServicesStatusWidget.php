<?php

namespace App\Filament\Dashboard\Widgets;

use Filament\Widgets\Widget;

class ServicesStatusWidget extends Widget
{
    protected string $view = 'filament.securegate.widgets.services-status-widget';

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [
            'services' => [
                [
                    'name' => 'Web Application',
                    'description' => 'Main application interface',
                    'status' => 'operational',
                ],
                [
                    'name' => 'API',
                    'description' => 'REST API services',
                    'status' => 'operational',
                ],
                [
                    'name' => 'Database',
                    'description' => 'Data storage and retrieval',
                    'status' => 'operational',
                ],
                [
                    'name' => 'File Storage',
                    'description' => 'Document and media storage',
                    'status' => 'operational',
                ],
                [
                    'name' => 'Email Delivery',
                    'description' => 'Notification and email system',
                    'status' => 'operational',
                ],
                [
                    'name' => 'Authentication',
                    'description' => 'Login and user authentication',
                    'status' => 'operational',
                ],
            ],
        ];
    }
}

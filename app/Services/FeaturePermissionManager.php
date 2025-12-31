<?php

namespace App\Services;

class FeaturePermissionManager
{
    /**
     * Define the mapping between high-level features and their required permissions.
     * This is the source of truth for the dynamic permission system.
     */
    public static function getFeaturePermissions(): array
    {
        return [
            'reservations' => [
                'view_reservations',
                'manage_reservations',
                'manage_reservation_settings',
                'view_tables',
                'manage_tables',
                'view_rooms',
                'manage_rooms',
            ],
            'pos' => [
                'view_pos',
                'create_orders',
                'manage_orders',
                'refund_orders',
                'view_payments',
            ],
            'menu' => [
                'view_menu',
                'manage_categories',
                'manage_menu_items',
                'manage_addons',
            ],
            'inventory' => [
                'view_inventory',
                'manage_inventory',
            ],
            'staff' => [
                'view_staff',
                'manage_staff',
                'manage_payouts',
                'manage_staff_bank_details',
                'manage_staff_schedules',
                'view_staff_chat',
            ],
            'finance' => [
                'view_wallet',
                'manage_wallet_deposits',
                'view_transactions',
            ],
            'marketing' => [
                'view_promotions',
                'manage_promotions',
                'manage_marketing_campaigns',
                'manage_marketing_templates',
            ],
            'analytics' => [
                'view_reports',
                'view_branch_analytics',
            ],
            'tasks' => [
                'view_tasks',
                'create_tasks',
                'manage_tasks',
                'receive_tasks',
            ],
            'ai_tools' => [
                'use_ai_image_generator',
                'use_ai_insights',
            ],
            'website_builder' => [
                'manage_website',
                'manage_site_pages',
            ],
        ];
    }

    /**
     * Get all unique permissions defined across all features.
     */
    public static function getAllPermissions(): array
    {
        $allPermissions = [];
        foreach (self::getFeaturePermissions() as $permissions) {
            $allPermissions = array_merge($allPermissions, $permissions);
        }
        return array_unique($allPermissions);
    }

    /**
     * Get permissions for a specific set of enabled features.
     */
    public static function getPermissionsByFeatures(array $features): array
    {
        $permissions = [];
        $mapping = self::getFeaturePermissions();

        foreach ($features as $feature) {
            if (isset($mapping[$feature])) {
                $permissions = array_merge($permissions, $mapping[$feature]);
            }
        }

        return array_unique($permissions);
    }
}

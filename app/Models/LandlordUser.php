<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;

class LandlordUser extends User
{
    protected $connection = 'landlord';
    protected $table = 'users';

    /**
     * Disable activity logging for LandlordUser to prevent tenant database conflicts
     * when the connection has switched but the model is being deleted/updated.
     */
    public static function bootLogsActivity(): void
    {
        // Override parent trait boot method to do nothing
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->dontLogFillable();
    }
}

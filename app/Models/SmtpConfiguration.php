<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class SmtpConfiguration extends Model
{
    protected $connection = 'landlord'; // Assuming shared settings are in landlord DB

    protected $fillable = [
        'name',
        'provider',
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'api_key',
        'api_region',
        'from_email',
        'from_name',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'api_key',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Optional: Encrypt password/api_key mutators if desired
    // public function setPasswordAttribute($value)
    // {
    //     $this->attributes['password'] = Crypt::encryptString($value);
    // }

    // public function getPasswordAttribute($value)
    // {
    //     try {
    //         return Crypt::decryptString($value);
    //     } catch (\Exception $e) {
    //         return $value;
    //     }
    // }
}

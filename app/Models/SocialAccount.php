<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Crypt;

class SocialAccount extends Model
{
    protected $connection = 'tenant';
    

    protected $fillable = [
        'platform',
        'external_account_id',
        'credentials',
        'name',
        'is_active',
    ];

    protected $hidden = [
        'credentials',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Interact with the credentials attribute.
     * Automatically encrypts/decrypts the credentials JSON.
     */
    protected function credentials(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? json_decode(Crypt::decryptString($value), true) : [],
            set: fn($value) => Crypt::encryptString(json_encode($value)),
        );
    }
}

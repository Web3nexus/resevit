<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tenant extends Model
{
    use HasFactory;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Boot the model and assign a UUID when creating if none provided.
     */
    protected static function booted()
    {
        static::creating(function (Tenant $tenant) {
            if (empty($tenant->{$tenant->getKeyName()})) {
                $tenant->{$tenant->getKeyName()} = (string) Str::uuid();
            }
        });
    }
    protected $fillable = [
        'name',
        'slug',
        'domain',
        'database_name',
        'owner_user_id',
        'status',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }
}

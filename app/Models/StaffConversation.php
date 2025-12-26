<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StaffConversation extends Model
{
    protected $connection = 'tenant';
    protected $fillable = ['type', 'name'];

    public function messages(): HasMany
    {
        return $this->hasMany(StaffMessage::class);
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(TenantUser::class, 'staff_conversation_participants', 'staff_conversation_id', 'user_id');
    }
}

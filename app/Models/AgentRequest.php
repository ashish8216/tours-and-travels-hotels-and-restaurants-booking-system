<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentRequest extends Model
{
    protected $fillable = [
        'business_name',
        'owner_name',
        'email',
        'phone',
        'business_type',
        'address',
        'message',
        'status',
    ];

    protected $casts = [
        'business_type' => 'array',
    ];
}

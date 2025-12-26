<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_name',
        'business_type',
        'phone',
        'address',
    ];
    protected $casts = [
        'business_type' => 'array',
    ];

    /**
     * Automatically handle role when agent is deleted
     */
    protected static function booted()
    {
        static::deleting(function (Agent $agent) {
             logger('AGENT DELETING EVENT FIRED', [
            'agent_id' => $agent->id,
            'user_id' => $agent->user_id,
        ]);
        });
    }

    // Relation to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Restaurant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'agent_id', 'name', 'description', 'cuisine_type', 'location',
        'phone', 'email', 'opening_time', 'closing_time', 'capacity',
        'image', 'status'
    ];

    protected $casts = [
        'opening_time' => 'datetime:H:i',
        'closing_time' => 'datetime:H:i',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function tables()
    {
        return $this->hasMany(RestaurantTable::class);
    }

    public function reservations()
    {
        return $this->hasMany(RestaurantReservation::class);
    }

    public function scopeByAgent($query, $agentId)
    {
        return $query->where('agent_id', $agentId);
    }
}

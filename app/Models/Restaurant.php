<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone',
        'opening_time',
        'closing_time'
    ];

    // Tables in this restaurant
    public function tables()
    {
        return $this->hasMany(RestaurantTable::class);
    }

    // Bookings for this restaurant
    public function bookings()
    {
        return $this->hasMany(RestaurantBooking::class);
    }

    // Agents managing this restaurant
    public function agents()
    {
        return $this->belongsToMany(Agent::class, 'agent_restaurant');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantBooking extends Model
{
    protected $fillable = [
        'restaurant_id',
        'customer_id',
        'booking_date',
        'start_time',
        'end_time',
        'booking_source',
        'status'
    ];

    // Booking belongs to a restaurant
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    // Booking belongs to a customer
    public function customer()
    {
        return $this->belongsTo(User::class);
    }

    // Booking has many tables
    public function tables()
    {
        return $this->belongsToMany(RestaurantTable::class, 'restaurant_booking_tables');
    }
}

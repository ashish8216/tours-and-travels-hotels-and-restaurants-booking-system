<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantTable extends Model
{
    protected $fillable = [
        'restaurant_id',
        'table_number',
        'capacity',
        'status'
    ];

    // Belongs to a restaurant
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    // Bookings assigned to this table
    public function bookings()
    {
        return $this->belongsToMany(RestaurantBooking::class, 'restaurant_booking_tables');
    }
}

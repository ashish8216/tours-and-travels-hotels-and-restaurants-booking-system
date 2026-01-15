<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RestaurantTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id', 'table_number', 'table_name', 'capacity',
        'type', 'status'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function reservations()
    {
        return $this->hasMany(RestaurantReservation::class);
    }

    public function isAvailableFor($date, $time)
    {
        return !$this->reservations()
            ->where('reservation_date', $date)
            ->where('reservation_time', $time)
            ->whereIn('status', ['pending', 'confirmed', 'seated'])
            ->exists();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'room_id',
        'user_id',
        'guest_name',
        'guest_phone',
        'check_in',
        'check_out',
        'price_per_night',
        'total_amount',
        'status',
        'booking_source',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
    ];

    /* =======================
        Relationships
    ======================== */

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

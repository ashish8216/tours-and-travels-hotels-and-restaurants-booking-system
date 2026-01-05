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
        'status_updated_at',
        'status_notes'
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'status_updated_at' => 'datetime',
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

    /**
     * Relationship with status logs
     */
    public function statusLogs()
{
    return $this->hasMany(BookingStatusLog::class, 'booking_id')->latest();
}

    /**
     * Scope for different statuses
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCheckedIn($query)
    {
        return $query->where('status', 'checked_in');
    }

    public function scopeCheckedOut($query)
    {
        return $query->where('status', 'checked_out');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['confirmed', 'checked_in']);
    }
}

<?php

// app/Models/TourDate.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_id',
        'date',
        'start_time',
        'available_slots',
        'booked_slots',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Relationships
    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function bookings()
    {
        return $this->hasMany(TourBooking::class);
    }

    // Helper methods
    public function getRemainingSlots()
    {
        return $this->available_slots - $this->booked_slots;
    }

    public function hasAvailableSlots($requestedSlots = 1)
    {
        return $this->getRemainingSlots() >= $requestedSlots;
    }

    public function isFull()
    {
        return $this->booked_slots >= $this->available_slots;
    }

    public function isPast()
    {
        return $this->date < now()->toDateString();
    }

    public function isAvailable()
    {
        return $this->status === 'available'
            && !$this->isPast()
            && !$this->isFull();
    }

    // Automatically update status when slots change
    protected static function booted()
    {
        static::saving(function ($tourDate) {
            if ($tourDate->booked_slots >= $tourDate->available_slots) {
                $tourDate->status = 'full';
            } elseif ($tourDate->status === 'full' && $tourDate->booked_slots < $tourDate->available_slots) {
                $tourDate->status = 'available';
            }
        });
    }
}

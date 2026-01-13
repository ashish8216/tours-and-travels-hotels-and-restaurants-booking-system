<?php

// app/Models/Tour.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tour extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'agent_id',
        'title',
        'description',
        'location',
        'price',
        'max_people',
        'duration',
        'duration_hours',
        'status',
        'image',
        'images',
        'difficulty_level',
        'inclusions',
        'exclusions',
        'requirements',
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
    ];

    // Relationships
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function tourDates()
    {
        return $this->hasMany(TourDate::class);
    }

    public function bookings()
    {
        return $this->hasMany(TourBooking::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByAgent($query, $agentId)
    {
        return $query->where('agent_id', $agentId);
    }

    // Helper methods
    public function hasAvailableDates()
    {
        return $this->tourDates()
            ->where('date', '>=', now()->toDateString())
            ->where('status', 'available')
            ->exists();
    }

    public function getNextAvailableDate()
    {
        return $this->tourDates()
            ->where('date', '>=', now()->toDateString())
            ->where('status', 'available')
            ->whereColumn('available_slots', '>', 'booked_slots')
            ->orderBy('date')
            ->first();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RestaurantReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id', 'restaurant_table_id', 'user_id', 'customer_name',
        'customer_email', 'customer_phone', 'number_of_people', 'reservation_date',
        'reservation_time', 'status', 'special_requests', 'cancellation_reason',
        'agent_notes', 'agent_id', 'confirmed_at', 'cancelled_at', 'seated_at',
        'completed_at', 'reservation_number'
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'reservation_time' => 'datetime:H:i',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function table()
    {
        return $this->belongsTo(RestaurantTable::class, 'restaurant_table_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function scopeByAgent($query, $agentId)
    {
        return $query->where('agent_id', $agentId);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function confirm()
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);
    }

    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
            'cancelled_at' => now(),
        ]);
    }
}

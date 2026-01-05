<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingStatusLog extends Model
{
    protected $fillable = [
        'booking_id',
        'from_status',
        'to_status',
        'notes',
        'changed_by'
    ];

    public function booking()
    {

        return $this->belongsTo(RoomBooking::class, 'booking_id');
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

}

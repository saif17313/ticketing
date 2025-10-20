<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_schedule_id',
        'seat_number',
        'seat_type',
        'status',
        'booking_id',
        'locked_until',
    ];

    protected $casts = [
        'locked_until' => 'datetime',
    ];

    // Relationships
    public function busSchedule()
    {
        return $this->belongsTo(BusSchedule::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')
            ->where(function ($q) {
                $q->whereNull('locked_until')
                  ->orWhere('locked_until', '<', now());
            });
    }

    public function scopeBooked($query)
    {
        return $query->where('status', 'booked');
    }

    public function scopeLocked($query)
    {
        return $query->where('status', 'locked')
            ->where('locked_until', '>', now());
    }

    public function scopeForSchedule($query, $scheduleId)
    {
        return $query->where('bus_schedule_id', $scheduleId);
    }

    // Helper methods
    public function isAvailable()
    {
        return $this->status === 'available' && 
               (!$this->locked_until || $this->locked_until->isPast());
    }

    public function isBooked()
    {
        return $this->status === 'booked';
    }

    public function isLocked()
    {
        return $this->status === 'locked' && 
               $this->locked_until && 
               $this->locked_until->isFuture();
    }

    public function lock($minutes = 10)
    {
        $this->update([
            'status' => 'locked',
            'locked_until' => now()->addMinutes($minutes),
        ]);
    }

    public function unlock()
    {
        $this->update([
            'status' => 'available',
            'locked_until' => null,
        ]);
    }

    public function book($bookingId)
    {
        $this->update([
            'status' => 'booked',
            'booking_id' => $bookingId,
            'locked_until' => null,
        ]);
    }

    public function release()
    {
        $this->update([
            'status' => 'available',
            'booking_id' => null,
            'locked_until' => null,
        ]);
    }
}

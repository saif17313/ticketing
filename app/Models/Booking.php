<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_reference',
        'user_id',
        'bus_schedule_id',
        'total_seats',
        'total_amount',
        'booking_type',
        'status',
        'expires_at',
        'payment_deadline',
        'passenger_details',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'payment_deadline' => 'datetime',
        'passenger_details' => 'array',
        'total_amount' => 'decimal:2',
    ];

    // Generate unique booking reference
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_reference)) {
                $booking->booking_reference = 'BK' . strtoupper(Str::random(8)) . time();
            }
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function busSchedule()
    {
        return $this->belongsTo(BusSchedule::class);
    }

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    // Helper methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function isExpired()
    {
        return $this->status === 'expired' || ($this->expires_at && $this->expires_at->isPast());
    }

    public function isPaymentDeadlinePassed()
    {
        return $this->payment_deadline && $this->payment_deadline->isPast();
    }

    public function getTimeRemainingForPayment()
    {
        if (!$this->payment_deadline || $this->isPaymentDeadlinePassed()) {
            return null;
        }

        return now()->diffInMinutes($this->payment_deadline);
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function needsPayment()
    {
        return $this->status === 'pending' && !$this->isPaymentDeadlinePassed();
    }
}

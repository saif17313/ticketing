<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusSchedule extends Model
{
    protected $fillable = [
        'bus_id',
        'journey_date',
        'departure_time',
        'arrival_time',
        'base_fare',
        'available_seats',
        'status',
    ];

    protected $casts = [
        'journey_date' => 'date',
        'base_fare' => 'decimal:2',
    ];

    // Relationship: Schedule belongs to a bus
    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class, 'bus_id');
    }
}

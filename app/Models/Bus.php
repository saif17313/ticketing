<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bus extends Model
{
    protected $fillable = [
        'company_id',
        'route_id',
        'bus_number',
        'bus_model',
        'bus_type',
        'total_seats',
        'seat_layout',
        'amenities',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationship: Bus belongs to a company
    public function company(): BelongsTo
    {
        return $this->belongsTo(BusCompany::class, 'company_id');
    }

    // Relationship: Bus belongs to a route
    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    // Relationship: Bus has many schedules
    public function schedules(): HasMany
    {
        return $this->hasMany(BusSchedule::class, 'bus_id');
    }
}

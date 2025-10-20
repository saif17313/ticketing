<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Route extends Model
{
    protected $fillable = [
        'source_district_id',
        'destination_district_id',
        'distance_km',
        'estimated_duration_minutes',
        'is_popular',
        'is_active',
    ];

    protected $casts = [
        'distance_km' => 'decimal:2',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationship: Route has a source district
    public function sourceDistrict(): BelongsTo
    {
        return $this->belongsTo(District::class, 'source_district_id');
    }

    // Relationship: Route has a destination district
    public function destinationDistrict(): BelongsTo
    {
        return $this->belongsTo(District::class, 'destination_district_id');
    }

    // Relationship: A route has many buses
    public function buses(): HasMany
    {
        return $this->hasMany(Bus::class, 'route_id');
    }
}

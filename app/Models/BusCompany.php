<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusCompany extends Model
{
    protected $fillable = [
        'owner_id',
        'name',
        'logo',
        'email',
        'phone',
        'address',
        'license_number',
        'is_verified',
        'is_active',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationship: A company belongs to an owner (user)
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Relationship: A company has many buses
    public function buses(): HasMany
    {
        return $this->hasMany(Bus::class, 'company_id');
    }
}

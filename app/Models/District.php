<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = [
        'name',
        'bn_name',
    ];

    // No timestamps needed for districts (static data)
    public $timestamps = false;
}

<?php
// File path: app/Models/Park.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Park extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location'];

    public function routes(): HasMany
    {
        return $this->hasMany(TripRoute::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }
}

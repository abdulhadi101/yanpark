<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TripRoute extends Model
{
    use HasFactory;

    protected $table = 'trip_routes';

    protected $fillable = ['park_id', 'origin', 'destination', 'fare'];

    protected function casts(): array
    {
        return ['fare' => 'decimal:2'];
    }

    public function park(): BelongsTo
    {
        return $this->belongsTo(Park::class);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    public function label(): string
    {
        return "{$this->origin} â†’ {$this->destination}";
    }
}

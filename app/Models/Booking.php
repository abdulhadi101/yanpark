<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id', 'user_id', 'reference', 'seat_no', 'status',
        'walk_in_name', 'walk_in_phone',
    ];

    protected function casts(): array
    {
        return ['seat_no' => 'integer'];
    }

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function isWalkIn(): bool
    {
        return $this->user_id === null;
    }

    public function passengerName(): string
    {
        return $this->user?->name ?? $this->walk_in_name ?? 'Walk-in passenger';
    }

    public function passengerPhone(): ?string
    {
        return $this->user?->phone ?? $this->walk_in_phone;
    }
}

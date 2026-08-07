<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentService
{
    public function pay(Booking $booking, string $method = 'mock_card'): Payment
    {
        if ($booking->status === 'paid') {
            throw new \RuntimeException('This booking has already been paid for.');
        }

        return DB::transaction(function () use ($booking, $method) {
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'amount' => $booking->trip->route->fare,
                'method' => $method,
                'status' => 'successful',
                'reference' => 'PAY-'.strtoupper(Str::random(10)),
            ]);

            $booking->update(['status' => 'paid']);

            return $payment;
        });
    }
}

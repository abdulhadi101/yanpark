<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Str;

class PaymentService
{
    /**
     * Record a payment against a booking and mark it paid.
     *
     * This is a mock/demo payment step — no real gateway is called. To wire up
     * a real provider later (e.g. Paystack), swap the body of this method for
     * an API call and only mark the booking "paid" once the gateway confirms.
     */
    public function pay(Booking $booking, string $method = 'mock_card'): Payment
    {
        if ($booking->status === 'paid') {
            throw new \RuntimeException('This booking has already been paid for.');
        }

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'amount' => $booking->trip->route->fare,
            'method' => $method,
            'status' => 'successful',
            'reference' => 'PAY-'.strtoupper(Str::random(10)),
        ]);

        $booking->update(['status' => 'paid']);

        return $payment;
    }
}

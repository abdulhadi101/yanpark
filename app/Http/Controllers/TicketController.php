<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function show(Booking $booking): View
    {
        // A passenger may only view their own ticket; cashier/admin can view any
        // (walk-in bookings have no user_id at all, so only staff can view those).
        abort_unless(
            (! $booking->isWalkIn() && $booking->user_id === auth()->id())
                || in_array(auth()->user()->role, ['cashier', 'admin'], true),
            403
        );

        $booking->load(['trip.route', 'trip.vehicle', 'user', 'payment']);

        return view('tickets.show', compact('booking'));
    }
}

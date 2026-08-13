<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim($request->string('search')->toString());

        $bookings = Booking::query()
            ->with(['trip.route', 'trip.vehicle', 'user', 'payment'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('reference', 'like', "%{$search}%")
                        ->orWhere('walk_in_name', 'like', "%{$search}%")
                        ->orWhere('walk_in_phone', 'like', "%{$search}%")
                        ->orWhereRaw('CAST(seat_no AS CHAR) LIKE ?', ["%{$search}%"])
                        ->orWhereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('from'), fn ($q) => $q->whereHas('trip', fn ($q) => $q->whereDate('departure_time', '>=', $request->string('from'))))
            ->when($request->filled('to'), fn ($q) => $q->whereHas('trip', fn ($q) => $q->whereDate('departure_time', '<=', $request->string('to'))))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.bookings.index', [
            'bookings' => $bookings,
            'statusFilter' => $request->string('status')->toString(),
            'search' => $search,
            'from' => $request->string('from')->toString(),
            'to' => $request->string('to')->toString(),
        ]);
    }

    public function cancel(Booking $booking, BookingService $bookings): RedirectResponse
    {
        try {
            $bookings->cancel($booking);

            return back()->with('status', 'Booking cancelled.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}

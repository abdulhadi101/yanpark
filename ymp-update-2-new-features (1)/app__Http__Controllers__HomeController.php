<?php
// File path: app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\TripRoute;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Public welcome page (report 4.6.1): welcome message, nav, available
     * routes, Login/Register buttons. Logged-in users skip straight to
     * their own dashboard instead.
     */
    public function index(): View|RedirectResponse
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        $routes = TripRoute::with('park')->orderBy('origin')->get();

        return view('home', compact('routes'));
    }
}

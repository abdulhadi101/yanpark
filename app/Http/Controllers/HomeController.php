<?php

namespace App\Http\Controllers;

use App\Models\TripRoute;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        $routes = TripRoute::with('park')->orderBy('origin')->get();

        return view('home', compact('routes'));
    }
}

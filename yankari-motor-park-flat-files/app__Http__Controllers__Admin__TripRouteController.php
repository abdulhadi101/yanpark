<?php
// File path: app/Http/Controllers/Admin/TripRouteController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Park;
use App\Models\TripRoute;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TripRouteController extends Controller
{
    public function index(): View
    {
        $routes = TripRoute::with('park')->latest()->paginate(10);

        return view('admin.routes.index', compact('routes'));
    }

    public function create(): View
    {
        $parks = Park::orderBy('name')->get();

        return view('admin.routes.create', compact('parks'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        TripRoute::create($data);

        return redirect()->route('admin.routes.index')->with('status', 'Route added.');
    }

    public function edit(TripRoute $route): View
    {
        $parks = Park::orderBy('name')->get();

        return view('admin.routes.edit', compact('route', 'parks'));
    }

    public function update(Request $request, TripRoute $route): RedirectResponse
    {
        $route->update($this->validated($request));

        return redirect()->route('admin.routes.index')->with('status', 'Route updated.');
    }

    public function destroy(TripRoute $route): RedirectResponse
    {
        $route->delete();

        return redirect()->route('admin.routes.index')->with('status', 'Route removed.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'park_id' => ['required', 'exists:parks,id'],
            'origin' => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'fare' => ['required', 'numeric', 'min:0'],
        ]);
    }
}

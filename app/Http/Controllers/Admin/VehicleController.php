<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Park;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleController extends Controller
{
    public function index(): View
    {
        $vehicles = Vehicle::with(['park', 'driver'])->latest()->paginate(10);

        return view('admin.vehicles.index', compact('vehicles'));
    }

    public function create(): View
    {
        return view('admin.vehicles.create', [
            'parks' => Park::orderBy('name')->get(),
            'drivers' => Driver::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Vehicle::create($this->validated($request));

        return redirect()->route('admin.vehicles.index')->with('status', 'Vehicle added.');
    }

    public function edit(Vehicle $vehicle): View
    {
        return view('admin.vehicles.edit', [
            'vehicle' => $vehicle,
            'parks' => Park::orderBy('name')->get(),
            'drivers' => Driver::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $vehicle->update($this->validated($request, $vehicle->id));

        return redirect()->route('admin.vehicles.index')->with('status', 'Vehicle updated.');
    }

    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        $vehicle->delete();

        return redirect()->route('admin.vehicles.index')->with('status', 'Vehicle removed.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'park_id' => ['required', 'exists:parks,id'],
            'driver_id' => ['nullable', 'exists:drivers,id'],
            'plate_no' => ['required', 'string', 'max:20', 'unique:vehicles,plate_no,'.$ignoreId],
            'type' => ['required', 'in:bus,car,coaster'],
            'capacity' => ['required', 'integer', 'min:1', 'max:60'],
        ]);
    }
}

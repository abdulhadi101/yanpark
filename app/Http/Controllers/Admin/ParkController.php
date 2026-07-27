<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Park;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParkController extends Controller
{
    public function index(): View
    {
        $parks = Park::withCount(['vehicles', 'routes'])->latest()->paginate(10);

        return view('admin.parks.index', compact('parks'));
    }

    public function create(): View
    {
        return view('admin.parks.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
        ]);

        Park::create($data);

        return redirect()->route('admin.parks.index')->with('status', 'Park added.');
    }

    public function edit(Park $park): View
    {
        return view('admin.parks.edit', compact('park'));
    }

    public function update(Request $request, Park $park): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
        ]);

        $park->update($data);

        return redirect()->route('admin.parks.index')->with('status', 'Park updated.');
    }

    public function destroy(Park $park): RedirectResponse
    {
        $park->delete();

        return redirect()->route('admin.parks.index')->with('status', 'Park removed.');
    }
}

<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\BusCompany;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BusController extends Controller
{
    // Show all buses for the logged-in owner
    public function index()
    {
        $buses = Bus::whereHas('company', function($query) {
            $query->where('owner_id', Auth::id());
        })
        ->with(['company', 'route.sourceDistrict', 'route.destinationDistrict'])
        ->latest()
        ->paginate(10);
        
        return view('owner.buses.index', compact('buses'));
    }

    // Show form to create a new bus
    public function create()
    {
        // Get only this owner's companies
        $companies = BusCompany::where('owner_id', Auth::id())
            ->where('is_active', true)
            ->get();
        
        // Get all active routes
        $routes = Route::where('is_active', true)
            ->with(['sourceDistrict', 'destinationDistrict'])
            ->get();

        return view('owner.buses.create', compact('companies', 'routes'));
    }

    // Store a new bus
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => ['required', 'exists:bus_companies,id'],
            'route_id' => ['required', 'exists:routes,id'],
            'bus_number' => ['required', 'string', 'max:50', 'unique:buses,bus_number'],
            'bus_model' => ['required', 'string', 'max:100'],
            'bus_type' => ['required', 'in:AC,Non-AC'],
            'total_seats' => ['required', 'integer', 'min:10', 'max:60'],
            'seat_layout' => ['required', 'in:2x2,2x3,2x1'],
            'amenities' => ['nullable', 'string', 'max:500'],
        ], [
            'bus_number.unique' => 'This bus number is already registered.',
            'total_seats.min' => 'Bus must have at least 10 seats.',
            'total_seats.max' => 'Bus cannot have more than 60 seats.',
        ]);

        // Verify the company belongs to this owner
        $company = BusCompany::findOrFail($request->company_id);
        if ($company->owner_id !== Auth::id()) {
            abort(403, 'You can only add buses to your own companies.');
        }

        Bus::create([
            'company_id' => $request->company_id,
            'route_id' => $request->route_id,
            'bus_number' => strtoupper(trim($request->bus_number)),
            'bus_model' => trim($request->bus_model),
            'bus_type' => $request->bus_type,
            'total_seats' => $request->total_seats,
            'seat_layout' => $request->seat_layout,
            'amenities' => $request->amenities ? trim($request->amenities) : null,
        ]);

        return redirect()->route('owner.buses.index')
            ->with('success', 'Bus added successfully!');
    }

    // Show form to edit a bus
    public function edit(Bus $bus)
    {
        // Make sure the owner can only edit their own buses
        if ($bus->company->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $companies = BusCompany::where('owner_id', Auth::id())
            ->where('is_active', true)
            ->get();
        
        $routes = Route::where('is_active', true)
            ->with(['sourceDistrict', 'destinationDistrict'])
            ->get();

        return view('owner.buses.edit', compact('bus', 'companies', 'routes'));
    }

    // Update a bus
    public function update(Request $request, Bus $bus)
    {
        // Make sure the owner can only update their own buses
        if ($bus->company->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'company_id' => ['required', 'exists:bus_companies,id'],
            'route_id' => ['required', 'exists:routes,id'],
            'bus_number' => ['required', 'string', 'max:50', Rule::unique('buses')->ignore($bus->id)],
            'bus_model' => ['required', 'string', 'max:100'],
            'bus_type' => ['required', 'in:AC,Non-AC'],
            'total_seats' => ['required', 'integer', 'min:10', 'max:60'],
            'seat_layout' => ['required', 'in:2x2,2x3,2x1'],
            'amenities' => ['nullable', 'string', 'max:500'],
            'is_active' => ['boolean'],
        ]);

        // Verify the company belongs to this owner
        $company = BusCompany::findOrFail($request->company_id);
        if ($company->owner_id !== Auth::id()) {
            abort(403, 'You can only assign buses to your own companies.');
        }

        $bus->update([
            'company_id' => $request->company_id,
            'route_id' => $request->route_id,
            'bus_number' => strtoupper(trim($request->bus_number)),
            'bus_model' => trim($request->bus_model),
            'bus_type' => $request->bus_type,
            'total_seats' => $request->total_seats,
            'seat_layout' => $request->seat_layout,
            'amenities' => $request->amenities ? trim($request->amenities) : null,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('owner.buses.index')
            ->with('success', 'Bus updated successfully!');
    }

    // Delete a bus
    public function destroy(Bus $bus)
    {
        // Make sure the owner can only delete their own buses
        if ($bus->company->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $bus->delete();

        return redirect()->route('owner.buses.index')
            ->with('success', 'Bus deleted successfully!');
    }
}

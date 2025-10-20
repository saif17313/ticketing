<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\BusCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BusCompanyController extends Controller
{
    // Show all companies for the logged-in owner
    public function index()
    {
        $companies = BusCompany::where('owner_id', Auth::id())
            ->latest()
            ->paginate(10);
            
        return view('owner.companies.index', compact('companies'));
    }

    // Show form to create a new company
    public function create()
    {
        return view('owner.companies.create');
    }

    // Store a new company
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150', 'unique:bus_companies,name'],
            'email' => ['required', 'email', 'max:100', 'unique:bus_companies,email'],
            'phone' => ['required', 'regex:/^01[0-9]{9}$/'],
            'address' => ['required', 'string', 'max:500'],
            'license_number' => ['required', 'string', 'max:50', 'unique:bus_companies,license_number'],
        ], [
            'name.unique' => 'This company name is already registered.',
            'email.unique' => 'This email is already used by another company.',
            'phone.regex' => 'Phone must be in Bangladesh format (01XXXXXXXXX).',
            'license_number.unique' => 'This license number is already registered.',
        ]);

        BusCompany::create([
            'owner_id' => Auth::id(),
            'name' => trim($request->name),
            'email' => strtolower($request->email),
            'phone' => $request->phone,
            'address' => trim($request->address),
            'license_number' => $request->license_number,
        ]);

        return redirect()->route('owner.companies.index')
            ->with('success', 'Bus company created successfully!');
    }

    // Show form to edit a company
    public function edit(BusCompany $company)
    {
        // Make sure the owner can only edit their own companies
        if ($company->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('owner.companies.edit', compact('company'));
    }

    // Update a company
    public function update(Request $request, BusCompany $company)
    {
        // Make sure the owner can only update their own companies
        if ($company->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150', Rule::unique('bus_companies')->ignore($company->id)],
            'email' => ['required', 'email', 'max:100', Rule::unique('bus_companies')->ignore($company->id)],
            'phone' => ['required', 'regex:/^01[0-9]{9}$/'],
            'address' => ['required', 'string', 'max:500'],
            'license_number' => ['required', 'string', 'max:50', Rule::unique('bus_companies')->ignore($company->id)],
            'is_active' => ['boolean'],
        ]);

        $company->update([
            'name' => trim($request->name),
            'email' => strtolower($request->email),
            'phone' => $request->phone,
            'address' => trim($request->address),
            'license_number' => $request->license_number,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('owner.companies.index')
            ->with('success', 'Company updated successfully!');
    }

    // Delete a company
    public function destroy(BusCompany $company)
    {
        // Make sure the owner can only delete their own companies
        if ($company->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $company->delete();

        return redirect()->route('owner.companies.index')
            ->with('success', 'Company deleted successfully!');
    }
}

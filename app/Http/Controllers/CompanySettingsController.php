<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanySettingsController extends Controller
{
    public function edit()
    {
        $company = auth()->user()->company;

        return view('company.settings', compact('company'));
    }

    public function update(Request $request)
    {
        $company = auth()->user()->company;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'tax_number' => 'nullable|string|max:100',
        ]);

        $company->update($validated);

        return redirect()
            ->route('company.settings')
            ->with('success', 'Company settings updated successfully.');
    }
}
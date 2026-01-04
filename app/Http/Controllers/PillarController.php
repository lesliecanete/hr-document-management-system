<?php

namespace App\Http\Controllers;

use App\Models\HRPillar;
use Illuminate\Http\Request;

class PillarController extends Controller
{
    public function index()
    {
        $pillars = HRPillar::latest()->paginate(10);
        return view('settings.pillars.index', compact('pillars'));
    }

    public function create()
    {
        return view('settings.pillars.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:pillars,name',
            'description' => 'nullable|string|max:500',
            'is_active' => 'required|boolean'
        ]);

        HRPillar::create($validated);

        return redirect()->route('pillars.index')
            ->with('success', 'HR Pillar created successfully.');
    }

    public function edit(HRPillar $pillar)
    {
        return view('settings.pillars.edit', compact('pillar'));
    }

    public function update(Request $request, HRPillar $pillar)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:pillars,name,' . $pillar->id,
            'description' => 'nullable|string|max:500',
            'is_active' => 'required|boolean'
        ]);

        $pillar->update($validated);

        return redirect()->route('pillars.index')
            ->with('success', 'HR  updated successfully.');
    }

    public function destroy(HRPillar $pillar)
    {
        // Check if pillar has document types before deleting
        if ($pillar->documentTypes()->exists()) {
            return redirect()->route('pillars.index')
                ->with('error', 'Cannot delete pillar because it has associated document types.');
        }

        $pillar->delete();

        return redirect()->route('pillars.index')
            ->with('success', 'HR Pillar deleted successfully.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    public function index()
    {
        $applicants = Applicant::latest()->paginate(10);
        return view('submitting-parties.index', compact('applicants'));
    }

    public function create()
    {
        return view('submitting-parties.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:applicants,email',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
        ]);

        Applicant::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'position' => $request->position,
            'status' => 'active',
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('submitting-parties.index')
            ->with('success', 'Submitting party created successfully.');
    }

    // CHANGED PARAMETER NAME to match route {submitting_party}
    public function show(Applicant $submitting_party)
    {
        $submitting_party->load('documents.documentType');
        return view('submitting-parties.show', ['applicant' => $submitting_party]); // Pass as 'applicant' to view
    }

    // CHANGED PARAMETER NAME to match route {submitting_party}
    public function edit(Applicant $submitting_party)
    {
        if (!auth()->user()->canEditApplicant($submitting_party)) {
            abort(403, 'You can only edit submitting parties you added.');
        }
        return view('submitting-parties.edit', ['applicant' => $submitting_party]); // Pass as 'applicant' to view
    }

    // CHANGED PARAMETER NAME to match route {submitting_party}
    public function update(Request $request, Applicant $submitting_party)
    {
        if (!auth()->user()->canEditApplicant($submitting_party)) {
            abort(403, 'You can only edit submitting parties you added.');
        }
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:applicants,email,' . $submitting_party->id,
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'status' => 'required|in:active,hired,rejected,withdrawn',
        ]);

        $submitting_party->update($request->all());

        return redirect()->route('submitting-parties.index')
            ->with('success', 'Submitting party updated successfully.');
    }

    // CHANGED PARAMETER NAME to match route {submitting_party}
    public function destroy(Applicant $submitting_party)
    {
        if (!auth()->user()->canDeleteApplicant($submitting_party)) {
            abort(403, 'You do not have permission to delete submitting parties.');
        }
        
        $submitting_party->delete();

        return redirect()->route('submitting-parties.index')
            ->with('success', 'Submitting party deleted successfully.');
    }
}
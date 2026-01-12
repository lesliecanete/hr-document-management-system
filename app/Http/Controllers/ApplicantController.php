<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    public function index()
    {
        $applicants = Applicant::latest()->paginate(10);
        return view('applicants.index', compact('applicants'));
    }

    public function create()
    {
        return view('applicants.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:applicants,email',
            'phone' => 'nullable|string|max:20',
        ]);

        Applicant::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => 'active',
            'user_id' => auth()->id(), // ✅ ADD THIS LINE

        ]);

        return redirect()->route('applicants.index')
            ->with('success', 'Applicant created successfully.');
    }

    public function show(Applicant $applicant)
    {
        $applicant->load('documents.documentType');
        return view('applicants.show', compact('applicant'));
    }

    public function edit(Applicant $applicant)
    {
        if (!auth()->user()->canEditApplicant($applicant)) {
            abort(403, 'You can only edit applicants you added.');
        }
        return view('applicants.edit', compact('applicant'));
    }

    public function update(Request $request, Applicant $applicant)
    {
        if (!auth()->user()->canEditApplicant($applicant)) {
            abort(403, 'You can only edit applicants you added.');
        }
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:applicants,email,' . $applicant->id,
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,hired,rejected,withdrawn',
        ]);

        $applicant->update($request->all());

        return redirect()->route('applicants.index')
            ->with('success', 'Applicant updated successfully.');
    }

    public function destroy(Applicant $applicant)
    {
        if (!auth()->user()->canDeleteApplicant($applicant)) {
            abort(403, 'You do not have permission to delete applicants.');
        }
        
        $applicant->delete();

        return redirect()->route('applicants.index')
            ->with('success', 'Applicant deleted successfully.');
    }
}   
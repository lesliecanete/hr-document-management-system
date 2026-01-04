<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use App\Models\HRPillar;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    public function documentTypes()
    {
        $documentTypes = DocumentType::with('pillar')->orderBy('name')->paginate(10);
        $pillars = HRPillar::where('is_active', true)->get();
        
        return view('document-types.index', compact('documentTypes', 'pillars'));
    }

    public function storeDocumentType(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'pillar_id' => 'required|exists:hr_pillars,id',
            'retention_years' => 'required|integer|min:1|max:100',
            'description' => 'nullable|string',
        ]);

        DocumentType::create([
            'name' => $request->name,
            'pillar_id' => $request->pillar_id,
            'retention_years' => $request->retention_years,
            'description' => $request->description,
            'requires_applicant' => $request->has('requires_applicant'),
        ]);

        return redirect()->route('document-types.index')->with('success', 'Document type created successfully.');
    }

    public function updateDocumentType(Request $request, DocumentType $documentType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'pillar_id' => 'required|exists:hr_pillars,id',
            'retention_years' => 'required|integer|min:1|max:100',
            'description' => 'nullable|string',
        ]);

        $documentType->update([
            'name' => $request->name,
            'pillar_id' => $request->pillar_id,
            'retention_years' => $request->retention_years,
            'description' => $request->description,
            'requires_applicant' => $request->has('requires_applicangt'),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('document-types.index')->with('success', 'Document type updated successfully.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use App\Models\HRPillar;
use Illuminate\Http\Request;

class DocumentTypeController extends Controller
{
    public function index()
    {
        $documentTypes = DocumentType::with('pillar')->latest()->paginate(10);
        $pillars = HRPillar::where('is_active', true)->get(); // Add this line
        
        return view('settings.document-types', compact('documentTypes', 'pillars')); // Add pillars to compact
    }

    public function create()
    {
        $pillars = HRPillar::where('is_active', true)->get();
        return view('settings.document-types.create', compact('pillars'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'pillar_id' => 'required|exists:hr_pillars,id',
            'retention_years' => 'required|integer|min:0',
            'requires_person' => 'boolean',
            'description' => 'nullable|string',
        ]);

        DocumentType::create([
            'name' => $request->name,
            'pillar_id' => $request->pillar_id,
            'retention_years' => $request->retention_years,
            'requires_person' => $request->requires_person ?? false,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return redirect()->route('document-types.index')
            ->with('success', 'Document type created successfully.');
    }

    public function edit(DocumentType $documentType)
    {
        $pillars = HRPillar::where('is_active', true)->get();
        return view('settings.document-types.edit', compact('documentType', 'pillars'));
    }

    public function update(Request $request, DocumentType $documentType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'pillar_id' => 'required|exists:hr_pillars,id',
            'retention_years' => 'required|integer|min:1',
            'requires_person' => 'boolean',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $documentType->update([
            'name' => $request->name,
            'pillar_id' => $request->pillar_id,
            'retention_years' => $request->retention_years,
            'requires_person' => $request->requires_person ?? false,
            'description' => $request->description,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('document-types.index')
            ->with('success', 'Document type updated successfully.');
    }

    public function destroy(DocumentType $documentType)
    {
        // Check if document type is being used
        if ($documentType->documents()->exists()) {
            return redirect()->route('document-types.index')
                ->with('error', 'Cannot delete document type that is in use.');
        }

        $documentType->delete();

        return redirect()->route('document-types.index')
            ->with('success', 'Document type deleted successfully.');
    }
}
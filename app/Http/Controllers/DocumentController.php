<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Applicant;
use App\Models\HRPillar;
use Illuminate\Support\Facades\DB; // Add this at the top
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::with(['documentType', 'applicant', 'uploadedBy']);

        if ($request->filled('pillar')) {
            $query->whereHas('documentType.pillar', function($q) use ($request) {
                $q->where('name', $request->pillar);
            });
        }

        if ($request->filled('document_type_id')) {
            $query->where('document_type_id', $request->document_type_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%")
                  ->orWhereHas('applicant', function($q) use ($request) {
                      $q->where('first_name', 'like', "%{$request->search}%")
                        ->orWhere('last_name', 'like', "%{$request->search}%")
                        ->orWhere('applicant_id', 'like', "%{$request->search}%");
                  });
            });
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(20);
        $pillars = HRPillar::where('is_active', true)->get();
        $documentTypes = DocumentType::where('is_active', true)->get();

        return view('documents.index', compact('documents', 'pillars', 'documentTypes'));
    }

    public function create()
    {
        $pillars = HRPillar::where('is_active', true)->get();
        $applicants = Applicant::where('status', 'active')->get();
        
        return view('documents.create', compact('pillars', 'applicants'));
    }

    public function getDocumentTypes($pillarId)
    {
        try {
            \Log::info("Fetching document types for pillar: {$pillarId}");
            
            // Validate that pillarId is a number
            if (!is_numeric($pillarId)) {
                \Log::error("Invalid pillar ID: {$pillarId}");
                return response()->json(['error' => 'Invalid pillar ID'], 400);
            }

            // Check if the pillar exists
            $pillarExists = HRPillar::where('id', $pillarId)->exists();
            if (!$pillarExists) {
                \Log::error("Pillar not found: {$pillarId}");
                return response()->json(['error' => 'Pillar not found'], 404);
            }

            // Get document types
            $documentTypes = DocumentType::where('pillar_id', $pillarId)
                ->where('is_active', true)
                ->get(['id', 'name', 'retention_years', 'requires_person']);

            \Log::info("Found {$documentTypes->count()} document types for pillar {$pillarId}");
            
            return response()->json($documentTypes);
            
        } catch (\Exception $e) {
            \Log::error('Error in getDocumentTypes: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
    public function store(Request $request)
    {
        // Validate all fields including new applicant fields
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'document_type_id' => 'required|exists:document_types,id',
            'applicant_id' => 'nullable|exists:applicants,id',
            'new_first_name' => 'nullable|string|max:255',
            'new_last_name' => 'nullable|string|max:255',
            'new_email' => 'nullable|email|max:255|unique:applicants,email',
            'new_phone' => 'nullable|string|max:20',
            'new_position' => 'nullable|string|max:255',
            'document_date' => 'required|date|before_or_equal:today',
            'document_file' => [
                'required',
                'file',
                'max:10240',
                'mimes:pdf,doc,docx,jpg,jpeg,png,txt',
            ],
            'description' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:500',
            'applicant_created' => 'nullable|in:0,1',
        ], [
            'document_file.mimes' => 'The document must be a file of type: pdf, doc, docx, jpg, jpeg, png, txt.',
            'document_date.before_or_equal' => 'Document date cannot be in the future.',
            'document_file.max' => 'The document size must not exceed 10MB.',
            'new_email.unique' => 'This email is already registered for another applicant.',
        ]);

        // Start database transaction
        DB::beginTransaction();

        try {
            $documentType = DocumentType::findOrFail($validated['document_type_id']);
            $applicantId = $validated['applicant_id'] ?? null;

            // ========== APPLICANT LOGIC ==========
            // Check if we need to create a new applicant
            if ($request->applicant_created == '1') {
                // Validate that all required fields are present for new applicant
                if (empty($validated['new_first_name']) || empty($validated['new_last_name']) || empty($validated['new_email'])) {
                    DB::rollBack();
                    return back()->withErrors([
                        'new_first_name' => 'First name is required when creating new applicant.',
                        'new_last_name' => 'Last name is required when creating new applicant.',
                        'new_email' => 'Email is required when creating new applicant.',
                    ])->withInput();
                }

                // Create new applicant
                $applicant = Applicant::create([
                    'first_name' => $validated['new_first_name'],
                    'last_name' => $validated['new_last_name'],
                    'email' => $validated['new_email'],
                    'phone' => $validated['new_phone'] ?? null,
                    'position' => $validated['new_position'] ?? null,
                    'status' => 'active',
                    'user_id' => auth()->id(),
                ]);

                $applicantId = $applicant->id;
            }

            // Validate applicant requirement based on document type
            if ($documentType->requires_applicant && !$applicantId) {
                DB::rollBack();
                return back()->withErrors(['applicant_id' => 'This document type requires an applicant.'])->withInput();
            }

            // Handle file upload
            $file = $request->file('document_file');
            $originalName = $file->getClientOriginalName();
            $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9.]/', '_', $originalName);
            $filePath = $file->storeAs('documents', $fileName, 'public');

            // Calculate expiry date based on retention policy
            $expiryDate = Carbon::parse($validated['document_date'])->addYears($documentType->retention_years);

            // Create the document
            Document::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'file_name' => $originalName,
                'file_path' => $filePath,
                'file_size' => $file->getSize(),
                'file_type' => $file->getMimeType(),
                'document_type_id' => $validated['document_type_id'],
                'applicant_id' => $applicantId,
                'uploaded_by' => auth()->id(),
                'document_date' => $validated['document_date'],
                'expiry_date' => $expiryDate,
                'notes' => $validated['notes'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('documents.index')->with('success', 'Document uploaded successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete the uploaded file if document creation failed
            if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            
            // Log the error
            \Log::error('Error uploading document: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()->withErrors(['error' => 'Error uploading document: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(Document $document)
    {
        return view('documents.show', compact('document'));
    }

    public function edit(Document $document)
    {
        if (!auth()->user()->canEditDocument($document)) {
            abort(403, 'You can only edit documents you uploaded.');
        }
        $pillars = HRPillar::where('is_active', true)->get();
        $applicants = applicant::where('status', 'active')->get();
        $documentTypes = DocumentType::where('pillar_id', $document->documentType->pillar_id)
            ->where('is_active', true)
            ->get();

        return view('documents.edit', compact('document', 'pillars', 'applicants', 'documentTypes'));
    }

    public function update(Request $request, Document $document)
    {
            
        if (!auth()->user()->canEditDocument($document)) {
            abort(403, 'You can only edit documents you uploaded.');
        }
        $request->validate([
            'title' => 'required|string|max:255',
            'document_type_id' => 'required|exists:document_types,id',
            'applicant_id' => 'nullable|exists:applicants,id',
            'document_date' => 'required|date',
            'document_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $updateData = [
            'title' => $request->title,
            'document_type_id' => $request->document_type_id,
            'applicant_id' => $request->applicant_id,
            'document_date' => $request->document_date,
            'description' => $request->description,
            'notes' => $request->notes,
        ];

        // Handle file upload if a new file is provided
        if ($request->hasFile('document_file')) {
            // Delete old file if exists
            if ($document->document_file) {
                $this->deleteFile($document->document_file);
            }

            // Upload new file
            $file = $request->file('document_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $fileName, 'public');
            
            $updateData['document_file'] = $filePath;
            $updateData['file_name'] = $file->getClientOriginalName();
            $updateData['file_size'] = $file->getSize();
            $updateData['file_type'] = $file->getClientOriginalExtension();
        }

        // Calculate new expiry date if document type changed
        if ($document->document_type_id != $request->document_type_id) {
            $documentType = DocumentType::find($request->document_type_id);
            $updateData['expiry_date'] = now()->addYears($documentType->retention_years);
        }

        $document->update($updateData);

        // Update document status based on new expiry date
        $document->updateStatus();

        return redirect()->route('documents.show', $document)
            ->with('success', 'Document updated successfully.');
    }

    // Helper method to delete files
    private function deleteFile($filePath)
    {
        try {
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        } catch (\Exception $e) {
            \Log::error('Error deleting file: ' . $e->getMessage());
        }
    }

    public function download(Document $document)
    {
        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    public function destroy(Document $document)
    {
        if (!auth()->user()->canDeleteDocument($document)) {
            abort(403, 'You do not have permission to delete documents.');
        }
        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Document deleted successfully.');
    }

    public function archiveExpired()
    {
        $expiredDocuments = Document::where('expiry_date', '<=', Carbon::today())
            ->where('status', '!=', 'archived')
            ->get();

        foreach ($expiredDocuments as $document) {
            $document->update(['status' => 'archived']);
        }

        return back()->with('success', count($expiredDocuments) . ' documents archived.');
    }
}
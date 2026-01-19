<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\HRPillar;
use App\Models\Applicant;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        // Debug: Log what's happening
        Log::info('Dashboard accessed', ['user_id' => auth()->id()]);

        // Count documents expiring in next 3 months (90 days)
        $expiringSoonCount = Document::where('status', 'active')
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '>', Carbon::now()) // Not expired yet
            ->where('expiry_date', '<=', Carbon::now()->addDays(90)) // Within next 90 days (3 months)
            ->count();

        $stats = [
            'total_documents' => Document::count(),
            'active_documents' => Document::where('status', 'active')->count(),
            'expiring_soon' => $expiringSoonCount, // Updated to 3 months
            'total_applicants' => Applicant::where('status', 'active')->count(),
        ];

        // Debug: Check HR pillars
        $allPillars = HRPillar::where('is_active', true)->get();
        Log::info('HR Pillars found:', ['count' => $allPillars->count(), 'pillars' => $allPillars->pluck('name')]);

        // Get pillars with document count
        $pillars = HRPillar::where('is_active', true)->get();
        
        foreach ($pillars as $pillar) {
            $documentCount = DB::table('documents')
                ->join('document_types', 'documents.document_type_id', '=', 'document_types.id')
                ->where('document_types.pillar_id', $pillar->id)
                ->where('documents.status', 'active') // Only count active documents
                ->count();
                
            $pillar->documents_count = $documentCount;
            Log::info("Pillar {$pillar->name} has {$documentCount} documents");
        }

        $recentDocuments = Document::with(['documentType', 'applicant', 'uploadedBy'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get documents expiring in next 3 months
        $expiringDocuments = Document::where('status', 'active')
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '>', Carbon::now()) // Not expired yet
            ->where('expiry_date', '<=', Carbon::now()->addDays(90)) // Within next 90 days (3 months)
            ->with(['documentType', 'applicant'])
            ->orderBy('expiry_date', 'asc') // Soonest first
            ->take(10) // Show more since we have 3 months worth
            ->get();

        // Debug: Log final data
        Log::info('Final pillars data:', ['pillars_count' => $pillars->count()]);
        Log::info('Expiring documents count:', ['count' => $expiringDocuments->count()]);

        return view('dashboard', compact('stats', 'pillars', 'recentDocuments', 'expiringDocuments'));
    }
}
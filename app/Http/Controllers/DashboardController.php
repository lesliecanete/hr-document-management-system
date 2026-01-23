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
        // Get current date for consistent calculations
        $today = Carbon::now();
        $ninetyDaysFromNow = $today->copy()->addDays(90);
        
        // Count documents expiring in next 3 months (90 days) - Dynamic calculation
        $expiringSoonCount = Document::whereNotNull('expiry_date')
            ->where('expiry_date', '>', $today) // Not expired yet
            ->where('expiry_date', '<=', $ninetyDaysFromNow) // Within next 90 days
            ->count();

        $stats = [
            'total_documents' => Document::count(),
            'active_documents' => Document::where('status', 'active')->count(),
            'expiring_soon' => $expiringSoonCount,
            'expired_documents' => Document::whereNotNull('expiry_date')
                ->where('expiry_date', '<=', $today)
                ->where('status', '!=', 'archived')
                ->count(),
            'total_applicants' => Applicant::where('status', 'active')->count(),
        ];

        // Get pillars with document count
        $pillars = HRPillar::where('is_active', true)->get();
        
        foreach ($pillars as $pillar) {
            $documentCount = DB::table('documents')
                ->join('document_types', 'documents.document_type_id', '=', 'document_types.id')
                ->where('document_types.pillar_id', $pillar->id)
                ->where('documents.status', 'active')
                ->count();
                
            $pillar->documents_count = $documentCount;
        }

        $recentDocuments = Document::with(['documentType', 'applicant', 'uploadedBy'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get documents expiring in next 3 months - Dynamic calculation
        $expiringDocuments = Document::whereNotNull('expiry_date')
            ->where('expiry_date', '>', $today) // Not expired yet
            ->where('expiry_date', '<=', $ninetyDaysFromNow) // Within next 90 days
            ->with(['documentType', 'applicant'])
            ->orderBy('expiry_date', 'asc')
            ->take(10)
            ->get()
            ->map(function($document) use ($today) {
                // Calculate days remaining for each document
                $document->days_until_expiry = $today->diffInDays($document->expiry_date);
                return $document;
            });

        return view('dashboard', compact(
            'stats', 
            'pillars', 
            'recentDocuments', 
            'expiringDocuments',
            'today'
        ));
    }
    
    // Optional: Add a method to filter by custom days
    public function getExpiringDocuments($days = 90)
    {
        $today = Carbon::now();
        $targetDate = $today->copy()->addDays($days);
        
        return Document::whereNotNull('expiry_date')
            ->where('expiry_date', '>', $today)
            ->where('expiry_date', '<=', $targetDate)
            ->with(['documentType', 'applicant'])
            ->orderBy('expiry_date', 'asc')
            ->paginate(20);
    }
}
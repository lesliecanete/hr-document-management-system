<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\PillarController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\HRPillar;
use App\Models\DocumentType;

Route::get('/', function () {
    return redirect('/dashboard');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Documents
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
    Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    
    // Document Downloads
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::post('/documents/archive-expired', [DocumentController::class, 'archiveExpired'])->name('documents.archive');
    
    // Applicants
    Route::get('/applicants', [ApplicantController::class, 'index'])->name('applicants.index');
    Route::get('/applicants/create', [ApplicantController::class, 'create'])->name('applicants.create');
    Route::post('/applicants', [ApplicantController::class, 'store'])->name('applicants.store');
    Route::get('/applicants/{applicant}', [ApplicantController::class, 'show'])->name('applicants.show');
    Route::get('/applicants/{applicant}/edit', [ApplicantController::class, 'edit'])->name('applicants.edit');
    Route::put('/applicants/{applicant}', [ApplicantController::class, 'update'])->name('applicants.update');
    Route::delete('/applicants/{applicant}', [ApplicantController::class, 'destroy'])->name('applicants.destroy');
    
    // Settings Routes
    Route::prefix('settings')->group(function () {
        // Users Management (Admin only)
        Route::middleware(['can:manage-users'])->group(function () {
            Route::get('/settings/users', [UserController::class, 'index'])->name('users.index');
            Route::get('/settings/users/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/settings/users', [UserController::class, 'store'])->name('users.store');
            Route::get('/settings/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/settings/users/{user}', [UserController::class, 'update'])->name('users.update');
            Route::delete('/settings/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        });
       // Document Types routes
        Route::get('/document-types', [DocumentTypeController::class, 'index'])->name('document-types.index');
        Route::get('/document-types/create', [DocumentTypeController::class, 'create'])->name('document-types.create');
        Route::post('/document-types', [DocumentTypeController::class, 'store'])->name('document-types.store'); // Add this line
        Route::get('/document-types/{documentType}/edit', [DocumentTypeController::class, 'edit'])->name('document-types.edit');
        Route::put('/document-types/{documentType}', [DocumentTypeController::class, 'update'])->name('document-types.update');
        Route::delete('/document-types/{documentType}', [DocumentTypeController::class, 'destroy'])->name('document-types.destroy');
        // HR Pillars routes
        Route::get('/pillars', [PillarController::class, 'index'])->name('pillars.index');
        Route::get('/pillars/create', [PillarController::class, 'create'])->name('pillars.create');
        Route::post('/pillars', [PillarController::class, 'store'])->name('pillars.store');
        Route::get('/pillars/{pillar}/edit', [PillarController::class, 'edit'])->name('pillars.edit');
        Route::put('/pillars/{pillar}', [PillarController::class, 'update'])->name('pillars.update');
        Route::delete('/pillars/{pillar}', [PillarController::class, 'destroy'])->name('pillars.destroy');
    });
    
    // Profile routes
    Route::middleware(['auth'])->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
        Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
    });
});

// Document Types API Route (for dynamic dropdown)
Route::get('/get-document-types/{pillar}', function ($pillarId) {
    try {
        $documentTypes = \App\Models\DocumentType::where('pillar_id', $pillarId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        return response()->json($documentTypes);
    } catch (\Exception $e) {
        \Log::error('Error fetching document types: ' . $e->getMessage());
        return response()->json([], 500);
    }
})->name('get-document-types');

// Home route (legacy)
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/get-document-types/{pillarId}', [DocumentController::class, 'getDocumentTypes'])->name('documents.get-types');

Route::get('/debug-data', function() {
    echo "<h2>Database Data Debug</h2>";
    
    // Check HR Pillars
    $pillars = \App\Models\HRPillar::withCount('documentTypes')->get();
    echo "<h3>HR Pillars ({$pillars->count()})</h3>";
    
    if ($pillars->count() > 0) {
        echo "<table border='1' style='width: 100%; border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Name</th><th>Slug</th><th>Active</th><th>Document Types Count</th></tr>";
        foreach ($pillars as $pillar) {
            echo "<tr>";
            echo "<td>{$pillar->id}</td>";
            echo "<td>{$pillar->name}</td>";
            echo "<td>{$pillar->slug}</td>";
            echo "<td>" . ($pillar->is_active ? 'Yes' : 'No') . "</td>";
            echo "<td>{$pillar->document_types_count}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: red;'>No HR pillars found in database!</p>";
    }
    
    // Check Document Types
    $documentTypes = \App\Models\DocumentType::with('pillar')->get();
    echo "<h3>Document Types ({$documentTypes->count()})</h3>";
    
    if ($documentTypes->count() > 0) {
        echo "<table border='1' style='width: 100%; border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Name</th><th>Pillar</th><th>Retention Years</th><th>Active</th></tr>";
        foreach ($documentTypes as $type) {
            echo "<tr>";
            echo "<td>{$type->id}</td>";
            echo "<td>{$type->name}</td>";
            echo "<td>{$type->pillar->name}</td>";
            echo "<td>{$type->retention_years}</td>";
            echo "<td>" . ($type->is_active ? 'Yes' : 'No') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: red;'>No document types found in database!</p>";
    }
    
    // Check if data is being passed to view correctly
    echo "<h3>Testing Dashboard Data</h3>";
    
    $testPillars = \App\Models\HRPillar::where('is_active', true)->get();
    echo "Active pillars found: " . $testPillars->count() . "<br>";
    
    foreach ($testPillars as $pillar) {
        $docCount = \App\Models\Document::whereHas('documentType', function($q) use ($pillar) {
            $q->where('pillar_id', $pillar->id);
        })->count();
        echo "Pillar '{$pillar->name}': {$docCount} documents<br>";
    }
});

Route::get('/test-document-data', function() {
    echo "<h2>Document Data Test</h2>";
    
    // Check pillars
    $pillars = \App\Models\HRPillar::all();
    echo "<h3>HR Pillars ({$pillars->count()})</h3>";
    foreach ($pillars as $pillar) {
        echo "<p><strong>{$pillar->name}</strong> (ID: {$pillar->id})</p>";
        
        // Check document types for this pillar
        $types = \App\Models\DocumentType::where('pillar_id', $pillar->id)->get();
        echo "Document Types: " . $types->count() . "<br>";
        foreach ($types as $type) {
            echo " - {$type->name} (Retention: {$type->retention_years} years)<br>";
        }
        echo "<hr>";
    }
    
    // Test the API endpoint
    echo "<h3>Test API Endpoint</h3>";
    if ($pillars->count() > 0) {
        $pillar = $pillars->first();
        echo "<p>Testing: <code>/get-document-types/{$pillar->id}</code></p>";
        
        $types = \App\Models\DocumentType::where('pillar_id', $pillar->id)->get();
        echo "Should return: " . $types->count() . " document types<br>";
        
        echo '<a href="/get-document-types/'.$pillar->id.'" target="_blank">Test API Link</a>';
    }
});

Route::get('/debug-upload', function() {
    echo "<h2>Upload Page Debug</h2>";
    
    // Check what data is available
    $pillars = \App\Models\HRPillar::where('is_active', true)->get();
    echo "<h3>Available HR Pillars ({$pillars->count()})</h3>";
    
    foreach ($pillars as $pillar) {
        echo "<p><strong>{$pillar->name}</strong> (ID: {$pillar->id})</p>";
        
        $documentTypes = \App\Models\DocumentType::where('pillar_id', $pillar->id)
            ->where('is_active', true)
            ->get();
            
        echo "Document Types: " . $documentTypes->count() . "<br>";
        foreach ($documentTypes as $type) {
            echo " - {$type->name} (Retention: {$type->retention_years} years)<br>";
        }
        echo "<hr>";
    }
    
    // Test the API endpoint
    echo "<h3>Test API Endpoint</h3>";
    if ($pillars->count() > 0) {
        $pillar = $pillars->first();
        echo "<p>Testing: <code>/get-document-types/{$pillar->id}</code></p>";
        
        $types = \App\Models\DocumentType::where('pillar_id', $pillar->id)->get();
        echo "Should return: " . $types->count() . " document types<br>";
        
        echo '<a href="/get-document-types/'.$pillar->id.'" target="_blank">Click here to test the API</a>';
    }
    
    echo '<br><br><a href="'.route('documents.create').'" class="btn btn-primary">Go to Upload Page</a>';
});
// Temporary test route - remove after testing
// Test Laravel built-in permissions
Route::get('/test-permissions', function () {
    $user = auth()->user();
    
    return response()->json([
        'user_id' => $user->id,
        'user_name' => $user->name,
        'user_email' => $user->email,
        
        // Test the specific gate
        'can_manage_users' => $user->can('manage-users'),
        'gate_allows' => Gate::allows('manage-users'),
        
        // Laravel built-in methods
        'is_authenticated' => auth()->check(),
        'is_staff' => $user->is_staff ?? 'N/A',
        'is_admin' => $user->is_admin ?? 'N/A',
        
        // Check direct abilities
        'abilities' => [
            'manage_users' => $user->can('manage-users'),
            'view_users' => $user->can('view-users'),
            'create_users' => $user->can('create-users'),
        ]
    ]);
})->middleware('auth');


Route::get('/debug-pillars', function () {
    echo "<h1>Debug HR Pillars</h1>";
    
    // Check if table exists
    $tableExists = \Illuminate\Support\Facades\Schema::hasTable('hr_pillars');
    echo "<p>Table exists: " . ($tableExists ? 'YES' : 'NO') . "</p>";
    
    if ($tableExists) {
        // Check columns
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('hr_pillars');
        echo "<h2>Columns:</h2>";
        echo "<pre>";
        print_r($columns);
        echo "</pre>";
        
        // Check records
        $pillars = HRPillar::all();
        echo "<h2>Pillars (" . $pillars->count() . "):</h2>";
        foreach ($pillars as $pillar) {
            echo "<p>ID: {$pillar->id}, Name: {$pillar->name}, Active: {$pillar->is_active}</p>";
        }
        
        // Check document types
        $documentTypes = DocumentType::all();
        echo "<h2>Document Types (" . $documentTypes->count() . "):</h2>";
        foreach ($documentTypes as $type) {
            echo "<p>ID: {$type->id}, Name: {$type->name}, Pillar ID: {$type->pillar_id}, Requires Applicant: {$type->requires_applicant}</p>";
        }
    }
    
    return "Debug complete";
});
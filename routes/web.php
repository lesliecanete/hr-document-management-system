<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\PillarController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    // Dashboard - All authenticated users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', [DashboardController::class, 'index'])->name('home');

    // =================== DOCUMENTS ROUTES ===================
    Route::prefix('documents')->name('documents.')->group(function () {
        // ✅ ALL ROLES: Can view, create, upload, download documents
        Route::get('/', [DocumentController::class, 'index'])->name('index');
        Route::get('/create', [DocumentController::class, 'create'])->name('create');
        Route::post('/', [DocumentController::class, 'store'])->name('store');
        Route::get('/{document}', [DocumentController::class, 'show'])->name('show');
        Route::get('/{document}/download', [DocumentController::class, 'download'])->name('download');
        
        // ⚠️ EDIT: Admin/HR Manager (all), HR Staff (only their own)
        // Permission checked in controller via $user->canEditDocument($document)
        Route::get('/{document}/edit', [DocumentController::class, 'edit'])->name('edit');
        Route::put('/{document}', [DocumentController::class, 'update'])->name('update');
        
        // ❌ DELETE: Admin & HR Manager ONLY (HR Staff cannot delete)
        // Checked via middleware
        Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('destroy');

            
        // ❌ ARCHIVE: Admin & HR Manager ONLY (HR Staff cannot archive)
        Route::post('/archive-expired', [DocumentController::class, 'archiveExpired'])->name('archive');
    });

    // =================== APPLICANTS ROUTES ===================
    Route::prefix('submitting-parties')->name('submitting-parties.')->group(function () {
        // ✅ ALL ROLES: Can view, create, add applicants
        Route::get('/', [ApplicantController::class, 'index'])->name('index');
        Route::get('/create', [ApplicantController::class, 'create'])->name('create');
        Route::post('/', [ApplicantController::class, 'store'])->name('store');
        Route::get('/{submitting_party}', [ApplicantController::class, 'show'])->name('show');
        
        // ⚠️ EDIT: Admin/HR Manager (all), HR Staff (only their own)
        // Permission checked in controller via $user->canEditApplicant($applicant)
        Route::get('/{submitting_party}/edit', [ApplicantController::class, 'edit'])->name('edit');
        Route::put('/{submitting_party}', [ApplicantController::class, 'update'])->name('update');
        
        // ❌ DELETE: Admin & HR Manager ONLY (HR Staff cannot delete)
        Route::delete('/{submitting_party}', [ApplicantController::class, 'destroy'])->name('destroy');
    });

    // =================== SETTINGS ROUTES ===================
    Route::prefix('settings')->group(function () {
        // USERS MANAGEMENT
        Route::prefix('users')->name('users.')->group(function () {
            // ✅ VIEW: Admin & HR Manager (HR Staff cannot view)
            Route::get('/', [UserController::class, 'index'])
                ->middleware('can:view-users')
                ->name('index');
            
            // ❌ MANAGE: Admin ONLY (Create, Edit, Delete)
            Route::middleware(['can:manage-users'])->group(function () {
                Route::get('/create', [UserController::class, 'create'])->name('create');
                Route::post('/', [UserController::class, 'store'])->name('store');
                Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
                Route::put('/{user}', [UserController::class, 'update'])->name('update');
                Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
            });
        });
        
        // DOCUMENT TYPES
        Route::prefix('document-types')->name('document-types.')->group(function () {
            // ✅ MANAGE: Admin & HR Manager ONLY (HR Staff cannot access)
            Route::middleware(['can:manage-document-types'])->group(function () {
                Route::get('/', [DocumentTypeController::class, 'index'])->name('index');
                Route::get('/create', [DocumentTypeController::class, 'create'])->name('create');
                Route::post('/', [DocumentTypeController::class, 'store'])->name('store');
                Route::get('/{documentType}/edit', [DocumentTypeController::class, 'edit'])->name('edit');
                Route::put('/{documentType}', [DocumentTypeController::class, 'update'])->name('update');
                Route::delete('/{documentType}', [DocumentTypeController::class, 'destroy'])->name('destroy');
            });
        });
        
        // HR PILLARS
        Route::prefix('pillars')->name('pillars.')->group(function () {
            // ✅ MANAGE: Admin & HR Manager ONLY (HR Staff cannot access)
            Route::middleware(['can:manage-pillars'])->group(function () {
                Route::get('/', [PillarController::class, 'index'])->name('index');
                Route::get('/create', [PillarController::class, 'create'])->name('create');
                Route::post('/', [PillarController::class, 'store'])->name('store');
                Route::get('/{pillar}/edit', [PillarController::class, 'edit'])->name('edit');
                Route::put('/{pillar}', [PillarController::class, 'update'])->name('update');
                Route::delete('/{pillar}', [PillarController::class, 'destroy'])->name('destroy');
            });
        });
    });
    
    // =================== PROFILE ROUTES ===================
    // ✅ ALL ROLES: Can manage their own profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
        Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
    });
});

// =================== PUBLIC/API ROUTES ===================
// ✅ ALL ROLES: API for document types dropdown (needed for document creation)
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

// Legacy home route
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/documents/{document}/qrcode', [DocumentController::class, 'generateQRCode'])
    ->name('documents.qrcode');

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

Route::get('/debug-permissions', function () {
    $user = auth()->user();
    
    return [
        'user_id' => $user->id,
        'role' => $user->role,
        'isHRManager' => $user->isHRManager(),
        'canManageDocumentTypes' => $user->canManageDocumentTypes(),
        'canManagePillars' => $user->canManagePillars(),
        'all_permissions' => $user->permissions
    ];
})->middleware('auth');
// Add to web.php temporarily
Route::get('/debug-applicant/{id}', function ($id) {
    $applicant = \App\Models\Applicant::find($id);
    $user = auth()->user();
    
    if (!$applicant) {
        return "Applicant not found";
    }
    
    return [
        'applicant_id' => $applicant->id,
        'applicant_user_id' => $applicant->user_id,
        'current_user_id' => $user->id,
        'current_user_role' => $user->role,
        'canEditApplicant_result' => $user->canEditApplicant($applicant),
        'isHRStaff' => $user->isHRStaff(),
        'comparison' => $applicant->user_id == $user->id,
    ];
})->middleware('auth');
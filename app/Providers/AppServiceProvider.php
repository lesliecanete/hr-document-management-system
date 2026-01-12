<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // =================== BASIC SYSTEM PERMISSIONS ===================
        Gate::define('manage-users', function ($user) {
            return $user->canManageUsers(); // Admin only
        });
        
        Gate::define('view-users', function ($user) {
            return $user->canViewUsers(); // Admin & HR Manager
        });
        
        Gate::define('manage-document-types', function ($user) {
            return $user->canManageDocumentTypes(); // Admin & HR Manager
        });
        
        Gate::define('manage-pillars', function ($user) {
            return $user->canManagePillars(); // Admin & HR Manager
        });

        // =================== DOCUMENT PERMISSIONS ===================
        // Simple gates for documents (object permissions handled in controller)
        Gate::define('delete-any-document', function ($user) {
            // Admin & HR Manager can delete any document
            return $user->isAdmin() || $user->isHRManager();
        });

        // =================== APPLICANT PERMISSIONS ===================
        // Simple gates for applicants (object permissions handled in controller)
        Gate::define('delete-any-applicant', function ($user) {
            // Admin & HR Manager can delete any applicant
            return $user->isAdmin() || $user->isHRManager();
        });
    }
}
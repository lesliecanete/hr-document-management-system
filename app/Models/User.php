<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'is_active', 'phone'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function documents()
    {
        return $this->hasMany(Document::class, 'uploaded_by');
    }
    
    public function applicants()
    {
        return $this->hasMany(Applicant::class, 'user_id');
    }

    // Role constants
    const ROLE_ADMIN = 'admin';
    const ROLE_HR_MANAGER = 'hr_manager';
    const ROLE_HR_STAFF = 'hr_staff';

    // =================== ROLE CHECKS ===================
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isHRManager()
    {
        return $this->role === self::ROLE_HR_MANAGER;
    }

    public function isHRStaff()
    {
        return $this->role === self::ROLE_HR_STAFF;
    }

    // =================== BASIC SYSTEM PERMISSIONS ===================
    public function canManageUsers()
    {
        return $this->isAdmin();
    }

    public function canViewUsers()
    {
        return $this->isAdmin() || $this->isHRManager();
    }

    public function canManageDocumentTypes()
    {
        return $this->isAdmin() || $this->isHRManager();
    }

    public function canManagePillars()
    {
        return $this->isAdmin() || $this->isHRManager();
    }

    // =================== DOCUMENT PERMISSIONS ===================
    public function canUploadDocuments()
    {
        return true; // All roles can upload
    }

    public function canViewAllDocuments()
    {
        return true; // All roles can view all documents
    }

    public function canEditDocument($document)
    {
        if ($this->isAdmin() || $this->isHRManager()) {
            return true; // Admin & HR Manager can edit all
        }
        
        // HR Staff can only edit their own documents
        if ($this->isHRStaff()) {
            return $document->uploaded_by == null || $document->uploaded_by == $this->id;
        }
        
        return false;
    }

    public function canDeleteDocument($document)
    {
        // Only Admin & HR Manager can delete documents
        // HR Staff CANNOT delete any documents
        return $this->isAdmin() || $this->isHRManager();
    }

    public function canViewDocument($document)
    {
        return true; // All roles can view all documents
    }

    public function canDownloadDocument($document)
    {
        return true; // All roles can download all documents
    }

    // =================== APPLICANT PERMISSIONS ===================
    public function canViewApplicants()
    {
        return true; // All roles can view applicants
    }

    public function canAddApplicants()
    {
        return true; // All roles can add applicants
    }

    public function canEditApplicant($applicant)
    {
        if ($this->isAdmin() || $this->isHRManager()) {
            return true;
        }
        
        if ($this->isHRStaff()) {
            // If user_id is null, assume it belongs to this user
            return $applicant->user_id === null || $applicant->user_id == $this->id;
        }
        
        return false;
    }

    public function canDeleteApplicant($applicant)
    {
        // Only Admin & HR Manager can delete applicants
        // HR Staff CANNOT delete any applicants
        return $this->isAdmin() || $this->isHRManager();
    }

    // =================== HELPER METHODS ===================
    public function getRoleDisplayAttribute()
    {
        return match($this->role) {
            self::ROLE_ADMIN => 'Administrator',
            self::ROLE_HR_MANAGER => 'HR Manager',
            self::ROLE_HR_STAFF => 'HR Staff',
            default => ucfirst($this->role),
        };
    }

    public function getRoleBadgeAttribute()
    {
        return match($this->role) {
            self::ROLE_ADMIN => 'badge bg-danger',
            self::ROLE_HR_MANAGER => 'badge bg-warning',
            self::ROLE_HR_STAFF => 'badge bg-secondary',
            default => 'badge bg-info',
        };
    }

    // Get all permissions as array
    public function getPermissionsAttribute()
    {
        $permissions = [];
        
        if ($this->isAdmin()) {
            $permissions = ['all'];
        } elseif ($this->isHRManager()) {
            $permissions = [
                'manage_documents',
                'manage_applicants', 
                'manage_document_types',
                'manage_pillars',
                'view_users'
            ];
        } elseif ($this->isHRStaff()) {
            $permissions = [
                'upload_documents',
                'view_documents',
                'edit_own_documents',
                'view_applicants',
                'add_applicants',
                'edit_own_applicants'
            ];
        }
        
        return $permissions;
    }
}
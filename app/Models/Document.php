<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Document extends Model
{
     protected static function boot()
    {
        parent::boot();

        // Delete associated file when document is deleted
        static::deleting(function ($document) {
            if ($document->document_file) {
                Storage::disk('public')->delete($document->document_file);
            }
        });
    }

    protected $fillable = [
        'title', 'description', 'file_name', 'file_path', 'file_size',
        'file_type', 'document_type_id', 'applicant_id', 'uploaded_by',
        'document_date', 'expiry_date', 'status', 'notes'
    ];

    protected $casts = [
        'document_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

   public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function pillar()
    {
        return $this->hasOneThrough(HRPillar::class, DocumentType::class, 'id', 'id', 'document_type_id', 'pillar_id');
    }

    public function updateStatus()
    {
        $today = Carbon::today();
        $expiryDate = Carbon::parse($this->expiry_date);

        if ($expiryDate->lessThanOrEqualTo($today)) {
            $this->status = 'archived';
        } elseif ($expiryDate->diffInDays($today) <= 30) {
            $this->status = 'expiring_soon';
        } else {
            $this->status = 'active';
        }

        $this->save();
    }

    public function isExpiringSoon()
    {
        return Carbon::parse($this->expiry_date)->diffInDays(Carbon::today()) <= 30;
    }
      // Get file name from document_file path
    public function getFileNameAttribute()
    {
        // First priority: file_name field (your actual storage)
        if (isset($this->attributes['file_name']) && $this->attributes['file_name']) {
            return $this->attributes['file_name'];
        }
        
        // Second priority: extract from file_path
        if (isset($this->attributes['file_path']) && $this->attributes['file_path']) {
            return basename($this->attributes['file_path']);
        }
        
        // Third priority: document_file (if used in future)
        if ($this->document_file) {
            return basename($this->document_file);
        }
        
        return 'Unknown';
    }
    // Check if document has any file attached
    public function getHasFileAttribute()
    {
        return !empty($this->attributes['file_name']) || 
            !empty($this->attributes['file_path']) || 
            !empty($this->document_file);
    }

    // Get the primary file path for display and download
    public function getDisplayFilePathAttribute()
    {
        if (isset($this->attributes['file_path']) && $this->attributes['file_path']) {
            return $this->attributes['file_path'];
        }
        
        if (isset($this->attributes['file_name']) && $this->attributes['file_name']) {
            return $this->attributes['file_name'];
        }
        
        if ($this->document_file) {
            return $this->document_file;
        }
        
        return null;
    }
    // Get file extension
    public function getFileExtensionAttribute()
    {
         $fileName = $this->file_name;
    
        if ($fileName && $fileName !== 'Unknown') {
            return pathinfo($fileName, PATHINFO_EXTENSION);
        }
        
        // Fallback to file_type field
        return $this->attributes['file_type'] ?? 'Unknown';
    }

    // Get human readable file type
    public function getFileTypeAttribute()
    {
        $extension = strtolower($this->file_extension);
        
        $fileTypes = [
            'pdf' => 'PDF Document',
            'doc' => 'Word Document',
            'docx' => 'Word Document',
            'jpg' => 'JPEG Image',
            'jpeg' => 'JPEG Image',
            'png' => 'PNG Image',
            'gif' => 'GIF Image',
            'txt' => 'Text File',
            'xls' => 'Excel Spreadsheet',
            'xlsx' => 'Excel Spreadsheet',
            'zip' => 'ZIP Archive',
            'rar' => 'RAR Archive',
        ];
        
        return $fileTypes[$extension] ?? (strtoupper($extension) . ' File');
    }

    // Get file icon for display
    public function getFileIconAttribute()
    {
        $extension = strtolower($this->file_extension);
        
        return match($extension) {
            'pdf' => 'fas fa-file-pdf text-danger',
            'doc', 'docx' => 'fas fa-file-word text-primary',
            'xls', 'xlsx' => 'fas fa-file-excel text-success',
            'jpg', 'jpeg', 'png', 'gif' => 'fas fa-file-image text-info',
            'zip', 'rar' => 'fas fa-file-archive text-warning',
            'txt' => 'fas fa-file-alt text-secondary',
            default => 'fas fa-file text-secondary'
        };
    }

    // Get file size in human readable format
    public function getFileSizeFormattedAttribute()
    {
        // First try to use the file_size field if it exists
        if (isset($this->attributes['file_size']) && $this->attributes['file_size']) {
            return $this->formatBytes($this->attributes['file_size']);
        }
        
        // If file_size doesn't exist, try to get from actual file
        if ($this->document_file) {
            try {
                $filePath = storage_path('app/public/' . $this->document_file);
                if (file_exists($filePath)) {
                    $size = filesize($filePath);
                    return $this->formatBytes($size);
                }
            } catch (\Exception $e) {
                // If we can't get the file size, continue to fallback
            }
        }
        
        return 'Unknown';
    }

    // Get the actual file path for download
    public function getFilePathAttribute()
    {
        if ($this->document_file) {
            return storage_path('app/public/' . $this->document_file);
        }
        
        // Fallback to old file_path field
        return $this->attributes['file_path'] ?? null;
    }

    // Get public URL for the file
    public function getFileUrlAttribute()
    {
        $filePath = $this->display_file_path;
        
        if ($filePath) {
            return asset('storage/' . $filePath);
        }
        
        return null;
    }

    // Helper method to format bytes
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    // Scope for active documents
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for expiring soon documents
    public function scopeExpiringSoon($query)
    {
        return $query->where('status', 'expiring_soon')
                    ->orWhere(function($q) {
                        $q->where('expiry_date', '>=', now())
                          ->where('expiry_date', '<=', now()->addDays(30));
                    });
    }

    // Scope for expired documents
    public function scopeExpired($query)
    {
        return $query->where('status', 'archived')
                    ->orWhere(function($q) {
                        $q->where('expiry_date', '<', now());
                    });
    }

    // Check if document has an associated file
    public function hasFile()
    {
        if ($this->document_file) {
            $filePath = storage_path('app/public/' . $this->document_file);
            return file_exists($filePath);
        }
        
        return !empty($this->file_path) && file_exists($this->file_path);
    }

}
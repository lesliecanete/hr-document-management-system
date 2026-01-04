<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class HRPillar extends Model
{
    protected $table = 'hr_pillars';

    protected $fillable = ['name', 'description', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Add slug if you're using it in your controller
    // protected $fillable = ['name', 'slug', 'description', 'is_active'];

    public function documentTypes(): HasMany
    {
        return $this->hasMany(DocumentType::class, 'pillar_id');
    }

    public function documents(): HasManyThrough
    {
        return $this->hasManyThrough(
            Document::class, 
            DocumentType::class,
            'pillar_id', // Foreign key on document_types table
            'document_type_id', // Foreign key on documents table
            'id', // Local key on hr_pillars table
            'id' // Local key on document_types table
        );
    }

    // Add scope for active pillars
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Optional: Add accessor for display name
    public function getDisplayNameAttribute()
    {
        return $this->name . ' (' . $this->documentTypes->count() . ' types)';
    }
}
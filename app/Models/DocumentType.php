<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    protected $fillable = [
        'name', 'pillar_id', 'retention_years', 'description', 
        'requires_employee', 'is_active'
    ];

    protected $casts = [
        'requires_employee' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function pillar()
    {
        return $this->belongsTo(HRPillar::class, 'pillar_id'); 
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
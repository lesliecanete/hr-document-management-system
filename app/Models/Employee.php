<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'employee_id', 'first_name', 'last_name', 'email',
        'department', 'position', 'hire_date', 'status'
    ];

    protected $casts = [
        'hire_date' => 'date',
    ];

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
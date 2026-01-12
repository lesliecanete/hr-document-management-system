<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'status',
        'user_id', // ✅ Make sure this exists

    ];

    protected $casts = [
        'application_date' => 'date',
    ];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    // Accessor to get the adder's name
    public function getAddedByNameAttribute()
    {
        return $this->user ? $this->user->name : 'Unknown';
    }

    // Get the user who added this applicant
    public function addedByUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
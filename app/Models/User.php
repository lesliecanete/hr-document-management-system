<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'is_active'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function documents()
    {
        return $this->hasMany(Document::class, 'uploaded_by');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isHRManager()
    {
        return in_array($this->role, ['admin', 'hr_manager']);
    }

    public function canManageUsers()
    {
        return $this->isAdmin();
    }
}
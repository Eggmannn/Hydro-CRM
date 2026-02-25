<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role'; // ✅ keep

    public $timestamps = true; // ✅ keep

    protected $fillable = [
        'user_id',
        'company_id',
        'role_type',
        'created_by',
        'is_primary_admin',
        'created_at', // ✅ keep (as requested)
    ];

    /* ---------------------------------------------
     | 🔗 Relationships
     * -------------------------------------------*/

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * ✅ NEW (non-breaking):
     * Who assigned / created this role
     */
    public function grantedBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /* ---------------------------------------------
     | ⚙️ Helper Methods (optional, safe additions)
     * -------------------------------------------*/

    /**
     * Determine if this role is Client
     */
    public function isClient(): bool
    {
        return $this->role_type === 'client';
    }

    /**
     * Determine if this role is Customer Admin
     */
    public function isCustomerAdmin(): bool
    {
        return $this->role_type === 'customer_admin';
    }

    /**
     * Determine if this role is Company Admin
     */
    public function isAdmin(): bool
    {
        return $this->role_type === 'admin';
    }

    /**
     * Determine if this role is Agent
     */
    public function isAgent(): bool
    {
        return $this->role_type === 'agent';
    }

    /**
     * Determine if this role is Viewer
     */
    public function isViewer(): bool
    {
        return $this->role_type === 'viewer';
    }
}

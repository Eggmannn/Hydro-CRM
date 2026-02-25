<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user'; // ✅ singular table name

    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /* ---------------------------------------------
     | 🔗 Relationships
     * -------------------------------------------*/

    // Company relationship
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    // Roles relationship
    public function roles()
    {
        return $this->hasMany(Role::class, 'user_id');
    }

    /* ---------------------------------------------
     | ⚙️ Helper Methods
     * -------------------------------------------*/

    /**
     * Get the user's primary role record (if any)
     */
    public function primaryRole()
    {
        return $this->roles()
            ->orderByDesc('is_primary_admin')
            ->first();
    }

    /**
     * Check if user has a specific role type
     */
    public function hasRole(string $roleType, ?int $companyId = null): bool
    {
        return $this->roles()
            ->where('role_type', $roleType)
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->exists();
    }

    /**
     * Determine if this user is a Customer Admin
     */
    public function isCustomerAdmin(): bool
    {
        return $this->hasRole('customer_admin', $this->company_id);
    }

    /**
     * Determine if this user is an Admin for their company
     */
    public function isCompanyAdmin(): bool
    {
        return $this->hasRole('admin', $this->company_id);
    }

    /**
     * Determine if this user is an Agent
     */
    public function isAgent(): bool
    {
        return $this->hasRole('agent', $this->company_id);
    }

    /**
     * Determine if this user is a Viewer
     */
    public function isViewer(): bool
    {
        return $this->hasRole('viewer', $this->company_id);
    }

    /**
     * ✅ NEW: Determine if this user is a Client
     * (external user under a company)
     */
    public function isClient(): bool
    {
        return $this->hasRole('client', $this->company_id);
    }

    /**
     * ✅ NEW: Generic company ownership check
     * (useful for authorization & IDOR protection)
     */
    public function belongsToCompany(int $companyId): bool
    {
        return $this->company_id === $companyId;
    }
}

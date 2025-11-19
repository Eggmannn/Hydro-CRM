<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CompanyAuthorization extends Model
{
    protected $fillable = [
        'crd_admin_id',
        'company_id',
        'granted_by',
        'granted_at',
        'expires_at',
        'reason',
    ];

    protected $dates = [
        'granted_at',
        'expires_at',
        'created_at',
        'updated_at',
    ];

    public function isActive()
    {
        return is_null($this->expires_at) || $this->expires_at->isFuture();
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function admin()
    {
        return $this->belongsTo(\App\Models\User::class, 'crd_admin_id');
    }
}

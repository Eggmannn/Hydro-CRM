<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'company';

    protected $fillable = [
        'name',
        'domain',
        'notes',
        'address',
        'email',
        'phone',
        'created_at',
        'updated_at',
    ];

    // Each company belongs to a CRM Admin
    public function crdAdmin()
    {
        return $this->belongsTo(CrdAdmin::class, 'created_by');
    }

    // Company has many users
    public function users()
    {
        return $this->hasMany(User::class, 'company_id');
    }
}

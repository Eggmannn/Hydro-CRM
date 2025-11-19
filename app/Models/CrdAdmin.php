<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class CrdAdmin extends Authenticatable
{
    use Notifiable;

    protected $table = 'crd_admin'; // 👈 matches your SQL table

    protected $fillable = [
        'name',
        'email',
        'password',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Example: a CRM Admin manages many companies
    public function companies()
    {
        return $this->hasMany(Company::class, 'created_by');
    }
}

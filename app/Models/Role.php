<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role'; // ✅ fix

    public $timestamps = true; // ✅ because `updated_at` doesn’t exist

    protected $fillable = [
        'user_id',
        'company_id',
        'role_type',
        'created_by',
        'is_primary_admin',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}

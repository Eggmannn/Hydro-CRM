<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contact';

    public $timestamps = true;

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'phone',
        'title',
        'notes',
        'deleted',
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'contact_id');
    }
}

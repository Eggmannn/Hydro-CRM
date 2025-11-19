<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'ticket';

    public $timestamps = true;

    protected $fillable = [
        'company_id',
        'contact_id',
        'assignee_id',
        'status',
        'priority',
        'subject',
        'body',
        'tags',
        'created_by',
        'updated_by',
        'deleted',
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class, 'ticket_id');
    }
}

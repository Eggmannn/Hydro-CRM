<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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

    /* ---------------------------------------------
     | 🔗 Relationships (existing + safe additions)
     * -------------------------------------------*/

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

    /**
     * ✅ NEW (non-breaking):
     * Ticket creator (client, agent, or admin)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * ✅ NEW (non-breaking):
     * Last user who updated the ticket
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class, 'ticket_id')->where('deleted', 0);
    }

    /* ---------------------------------------------
     | ⚙️ Helper Methods (safe additions)
     * -------------------------------------------*/

    /**
     * Check if ticket is soft-deleted
     */
    public function isDeleted(): bool
    {
        return (bool) $this->deleted;
    }

    /**
     * Check if ticket belongs to a company
     */
    public function belongsToCompany(int $companyId): bool
    {
        return $this->company_id === $companyId;
    }

    /**
     * Check if ticket was created by a specific user
     */
    public function isCreatedBy(int $userId): bool
    {
        return $this->created_by === $userId;
    }

    /**
     * Check if a user can be considered the owner
     * (used mainly for client access control)
     */
    public function isOwnedBy(User $user): bool
    {
        return $this->belongsToCompany($user->company_id)
            && $this->isCreatedBy($user->id);
    }
}

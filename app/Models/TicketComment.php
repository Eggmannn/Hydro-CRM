<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class TicketComment extends Model
{
    protected $table = 'ticket_comment';

    public $timestamps = true;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'body',
        'deleted',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

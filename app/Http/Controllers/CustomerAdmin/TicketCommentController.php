<?php

namespace App\Http\Controllers\CustomerAdmin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketComment;
use Illuminate\Http\Request;

class TicketCommentController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        $this->authorizeTicket($ticket);
        $data = $request->validate([
            'body' => ['required','string'],
        ]);
        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id'   => auth()->id(),
            'body'      => $data['body'],
            'deleted'   => 0,
        ]);
        return back()->with('success','Comment added.');
    }

    public function destroy(Ticket $ticket, TicketComment $comment)
    {
        $this->authorizeTicket($ticket);
        if ($comment->ticket_id !== $ticket->id || $comment->deleted) {
            abort(403);
        }
        $comment->update(['deleted'=>1]);
        return back()->with('success','Comment deleted.');
    }

    protected function authorizeTicket(Ticket $ticket)
    {
        if ($ticket->company_id !== auth()->user()->company_id || $ticket->deleted) {
            abort(403);
        }
    }
}

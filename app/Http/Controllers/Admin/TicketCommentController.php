<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketCommentController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        $companyId = Auth::user()->company_id;

        if ($ticket->company_id != $companyId || $ticket->deleted) {
            abort(403);
        }

        $validated = $request->validate([
            'body' => 'required|string',
        ]);

        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'body' => $validated['body'],
            'deleted' => 0,
        ]);

        return back()->with('success', 'Comment added.');
    }

    public function destroy(Ticket $ticket, TicketComment $comment)
    {
        $companyId = Auth::user()->company_id;

        if ($ticket->company_id != $companyId || $ticket->deleted) {
            abort(403);
        }

        if ($comment->ticket_id != $ticket->id) {
            abort(403);
        }

        $comment->deleted = 1;
        $comment->save();

        return back()->with('success', 'Comment deleted.');
    }
}

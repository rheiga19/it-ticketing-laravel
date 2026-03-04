<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketComment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class TicketCommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, Ticket $ticket): RedirectResponse
    {
        $user = auth()->user();

        // Only ticket creator, assigned admin, or any admin can comment
        $canComment = $ticket->user_id === $user->id ||
                      $ticket->assigned_to === $user->id ||
                      $user->hasRole(['admin', 'superadmin']);

        if (!$canComment) {
            abort(403, 'Anda tidak diizinkan menambahkan komentar pada tiket ini.');
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $ticket->comments()->create([
            'user_id' => $user->id,
            'message' => $validated['message'],
        ]);

        return redirect()->route('tickets.show', $ticket)
            ->with('status', 'Komentar berhasil ditambahkan.');
    }

    /**
     * Delete a comment.
     */
    public function destroy(TicketComment $comment): RedirectResponse
    {
        $user = auth()->user();
        $ticket = $comment->ticket;

        // Only comment author, ticket creator, assigned admin, or any admin can delete
        $canDelete = $comment->user_id === $user->id ||
                     $ticket->user_id === $user->id ||
                     $ticket->assigned_to === $user->id ||
                     $user->hasRole(['admin', 'superadmin']);

        if (!$canDelete) {
            abort(403, 'Anda tidak diizinkan menghapus komentar ini.');
        }

        $comment->delete();

        return redirect()->route('tickets.show', $ticket)
            ->with('status', 'Komentar berhasil dihapus.');
    }
}

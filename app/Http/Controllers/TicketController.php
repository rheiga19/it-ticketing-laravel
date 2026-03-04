<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketResolvedNotification;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TicketController extends Controller
{
    /**
     * Display a listing of tickets.
     * Regular users see only their own tickets.
     * Admins see all tickets.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        
        if ($user->hasRole('admin') || $user->hasRole('superadmin')) {
            $tickets = Ticket::with(['user', 'assignedAdmin']);
        } else {
            $tickets = $user->tickets()->with(['assignedAdmin']);
        }

        if ($status = $request->query('status')) {
            // allow partial matching like open or resolved
            $tickets = $tickets->where('status', $status);
        }

        $tickets = $tickets->latest()->get();

        return view('tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new ticket.
     * Only regular users can create tickets.
     */
    public function create(): View
    {
        return view('tickets.create');
    }

    /**
     * Store a newly created ticket in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'priority' => ['required', 'in:low,medium,high'],
            'attachments.*' => ['nullable', 'image', 'max:2048'],
            'attachments' => ['nullable', 'array', 'max:10'],
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'open';

        $ticket = Ticket::create($validated);

        // store attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('tickets', 'public');
                $ticket->attachments()->create(['path' => $path]);
            }
        }

        return redirect()->route('tickets.show', $ticket)
            ->with('status', "Ticket created successfully. ID tiket: {$ticket->ticket_number}");
    }

    /**
     * Display the specified ticket.
     */
    public function show(Ticket $ticket): View
    {
        $user = auth()->user();
        
        // Only allow viewing own ticket (if user) or any ticket (if admin)
        if (!$user->hasRole('admin') && !$user->hasRole('superadmin') && $ticket->user_id !== $user->id) {
            abort(403);
        }

        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing a ticket.
     * Only the ticket creator or admins can edit open tickets.
     */
    public function edit(Ticket $ticket): View
    {
        $user = auth()->user();
        
        // Only ticket creator or admin can edit
        if (!$user->hasRole('admin') && !$user->hasRole('superadmin') && $ticket->user_id !== $user->id) {
            abort(403);
        }

        $admins = User::whereHas('role', function($q) {
            $q->whereIn('name', ['admin', 'superadmin']);
        })->get();

        return view('tickets.edit', compact('ticket', 'admins'));
    }

    /**
     * Update a ticket in storage.
     * Regular users can only update their own open tickets (title, description, priority).
     * Admins can update any ticket (status, notes, assigned_to).
     */
    public function update(Request $request, Ticket $ticket): RedirectResponse
    {
        $user = auth()->user();
        
        if ($user->hasRole('admin') || $user->hasRole('superadmin')) {
            // Admin may only change status, assignment, notes, resolved_at, and add attachments
            $validated = $request->validate([
                'status' => ['sometimes', 'in:open,in_progress,resolved,closed'],
                'assigned_to' => ['nullable', 'exists:users,id'],
                'notes' => ['nullable', 'string'],
                'resolved_at' => ['nullable', 'date'],
                'attachments.*' => ['nullable', 'image', 'max:2048'],
                'attachments' => ['nullable', 'array', 'max:10'],
            ]);

            if (isset($validated['status']) && $validated['status'] === 'resolved' && !isset($validated['resolved_at'])) {
                $validated['resolved_at'] = now();
            }

            // Track status change untuk trigger notifikasi
            $oldStatus = $ticket->status;
            $ticket->update($validated);
            
            // Send notification jika status berubah menjadi resolved
            if ($oldStatus !== 'resolved' && $validated['status'] === 'resolved') {
                $ticket->user->notify(new TicketResolvedNotification($ticket));
            }

            if ($request->hasFile('attachments')) {
                // append new attachments, keep existing up to 10
                foreach ($request->file('attachments') as $file) {
                    if ($ticket->attachments()->count() < 10) {
                        $path = $file->store('tickets', 'public');
                        $ticket->attachments()->create(['path' => $path]);
                    }
                }
            }

            // if the form provided a redirect target, go there (this keeps admins on dashboard when updating)
            if ($request->filled('redirect_to')) {
                return redirect($request->input('redirect_to'))
                    ->with('status', 'Ticket updated successfully.');
            }
        } else {
            // Regular users can only update their own open tickets
            if ($ticket->user_id !== $user->id || $ticket->status !== 'open') {
                abort(403);
            }

            $validated = $request->validate([
                'title' => ['sometimes', 'string', 'max:255'],
                'description' => ['sometimes', 'string'],
                'priority' => ['sometimes', 'in:low,medium,high'],
            ]);

            $ticket->update($validated);
        }

        return redirect()->route('tickets.show', $ticket)->with('status', 'Ticket updated successfully.');
    }

    /**
     * Remove a ticket.
     * Only admins can delete tickets.
     */
    public function destroy(Ticket $ticket): RedirectResponse
    {
        $user = auth()->user();
        
        if (!$user->hasRole('admin') && !$user->hasRole('superadmin')) {
            abort(403);
        }

        $ticket->delete();

        return redirect()->route('tickets.index')->with('status', 'Ticket deleted successfully.');
    }
}

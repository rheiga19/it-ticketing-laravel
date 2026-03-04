<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\TicketAttachment;
use App\Models\TicketComment;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_number',
        'title',
        'description',
        'status',
        'priority',
        'user_id',
        'assigned_to',
        'notes',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who created this ticket
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin assigned to this ticket
     */
    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Scope for open tickets
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope for in-progress tickets
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope for resolved tickets
     */
    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    /**
     * Check if ticket is resolved or closed
     */
    public function isCompleted(): bool
    {
        return in_array($this->status, ['resolved', 'closed']);
    }

    /**
     * Attachments associated with this ticket
     */
    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }

    /**
     * Comments associated with this ticket
     */
    public function comments(): HasMany
    {
        return $this->hasMany(TicketComment::class)->latest();
    }

    /**
     * Boot model events to auto-generate ticket number on creation.
     */
    protected static function booted()
    {
        static::creating(function (Ticket $ticket) {
            // build ticket number: T - ddmmyyyyNN
            $datePart = now()->format('dmY');
            $countToday = self::whereDate('created_at', now()->toDateString())->count() + 1;
            $sequence = str_pad($countToday, 2, '0', STR_PAD_LEFT);
            $ticket->ticket_number = 'T - ' . $datePart . $sequence;
        });
    }
}

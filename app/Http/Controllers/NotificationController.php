<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Mark a notification as read
     */
    public function markAsRead(DatabaseNotification $notification): RedirectResponse
    {
        if ($notification->notifiable_id !== auth()->id()) {
            abort(403);
        }

        $notification->markAsRead();

        return back()->with('status', 'Notifikasi ditandai sebagai dibaca.');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): RedirectResponse
    {
        auth()->user()->notifications()->whereNull('read_at')->update(['read_at' => now()]);

        return back()->with('status', 'Semua notifikasi ditandai sebagai dibaca.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;

class AdminDashboardController extends Controller
{
    public function __invoke()
    {
        // Get all tickets for admin to manage status changes
        $tickets = Ticket::with(['user', 'assignedAdmin'])
            ->orderBy('created_at', 'desc')
            ->get();

        $statusOptions = [
            'open' => 'Open',
            'in_progress' => 'In Progress',
            'resolved' => 'Resolved',
            'closed' => 'Closed',
        ];

        return view('admin.admin-dashboard', compact('tickets', 'statusOptions'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    /**
     * Export semua tiket ke CSV (Admin only)
     */
    public function exportTickets(Request $request)
    {
        // Pastikan user adalah admin atau superadmin
        if (!Auth::user()->hasRole(['admin', 'superadmin'])) {
            abort(403, 'Unauthorized');
        }

        // Get filter dari request
        $status = $request->get('status');
        $priority = $request->get('priority');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Build query
        $query = Ticket::query();

        if ($status) {
            $query->where('status', $status);
        }

        if ($priority) {
            $query->where('priority', $priority);
        }

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $tickets = $query->with(['user', 'assignedAdmin'])->latest('created_at')->get();

        // Generate CSV
        $fileName = 'tiket-laporan-' . now()->format('Y-m-d-H-i-s') . '.csv';

        $headers = array(
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        );

        $columns = array('Tiket ID', 'Judul', 'User', 'Deskripsi', 'Status', 'Priority', 'Admin', 'Dibuat', 'Diupdate');

        $callback = function () use ($tickets, $columns) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, $columns);

            // Data
            foreach ($tickets as $ticket) {
                fputcsv($file, array(
                    $ticket->ticket_number,
                    $ticket->title,
                    $ticket->user->name,
                    substr($ticket->description, 0, 50) . '...',
                    strtoupper(str_replace('_', ' ', $ticket->status)),
                    strtoupper($ticket->priority),
                    $ticket->assignedAdmin?->name ?? '-',
                    $ticket->created_at->format('Y-m-d H:i'),
                    $ticket->updated_at->format('Y-m-d H:i'),
                ));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export tiket user-specific (untuk user view tiket mereka sendiri)
     */
    public function exportMyTickets(Request $request)
    {
        $user = Auth::user();
        $tickets = $user->tickets()->latest('created_at')->get();

        $fileName = 'tiket-saya-' . now()->format('Y-m-d-H-i-s') . '.csv';

        $headers = array(
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        );

        $columns = array('Tiket ID', 'Judul', 'Deskripsi', 'Status', 'Priority', 'Admin', 'Dibuat', 'Diupdate');

        $callback = function () use ($tickets, $columns) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, $columns);

            // Data
            foreach ($tickets as $ticket) {
                fputcsv($file, array(
                    $ticket->ticket_number,
                    $ticket->title,
                    substr($ticket->description, 0, 50) . '...',
                    strtoupper(str_replace('_', ' ', $ticket->status)),
                    strtoupper($ticket->priority),
                    $ticket->assignedAdmin?->name ?? '-',
                    $ticket->created_at->format('Y-m-d H:i'),
                    $ticket->updated_at->format('Y-m-d H:i'),
                ));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show export form
     */
    public function showExportForm()
    {
        if (!Auth::user()->hasRole(['admin', 'superadmin'])) {
            abort(403, 'Unauthorized');
        }

        return view('admin.export-form');
    }
}

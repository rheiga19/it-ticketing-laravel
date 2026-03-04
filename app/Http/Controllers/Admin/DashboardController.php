<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // breadcrumb array
        $breadcrumbs = [
            ['label' => 'Super Admin', 'url' => route('admin.superadmin.dashboard')],
            ['label' => 'Laporan Masuk Ticketing'],
            ['label' => 'Dashboard'],
        ];

        // compute ticket counts by day name (last 30 days)
        $counts = Ticket::selectRaw("DAYNAME(created_at) as day, count(*) as total")
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('day')
            ->pluck('total', 'day')
            ->toArray();

        // ensure all days exist
        $days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        $data = [];
        foreach($days as $d) {
            $data[] = $counts[$d] ?? 0;
        }

        // status summary counts
        $statusCounts = Ticket::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')->toArray();

        return view('admin.dashboard', compact('breadcrumbs', 'data', 'days', 'statusCounts'));
    }
}


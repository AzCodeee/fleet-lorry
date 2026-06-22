<?php
// ─────────────────────────────────────────────────────────────────────────────
// DashboardController.php
// ─────────────────────────────────────────────────────────────────────────────
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // TODO: Replace with real API/model calls
        $stats = [
            'total_revenue'   => 128450.00,
            'idle_lorries'    => 12,
            'active_projects' => 7,
            'active_tickets'  => 34,
            'active_drivers'  => 18,
        ];

        $fleetStatus = [
            'running'     => 18,
            'idle'        => 12,
            'maintenance' => 4,
        ];

        $revenueChart = [
            'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            'data'   => [12000, 19000, 15000, 22000, 18000, 26000, 24000],
        ];

        $recentTickets = collect(); // Replace: Ticket::with([...])->latest()->take(10)->get()

        return view('dashboard', compact('stats', 'fleetStatus', 'revenueChart', 'recentTickets'));
    }
}

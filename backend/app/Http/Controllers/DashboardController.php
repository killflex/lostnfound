<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Resources\DashboardResource;

class DashboardController extends Controller {
    public function getStatistics(Request $request){
        $currentMonth = Carbon::now()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();

        $totalTickets = Ticket::whereBetween('created_at', [$currentMonth, $endOfMonth])->count();

        $activeTickets = Ticket::whereBetween('created_at', [$currentMonth, $endOfMonth])
            ->where('status', '!=', 'closed')
            ->count();

        $resolvedTickets = Ticket::whereBetween('created_at', [$currentMonth, $endOfMonth])
            ->where('status', 'resolved')
            ->count();

        $avgresolutionTime = Ticket::whereBetween('created_at', [$currentMonth, $endOfMonth])
            ->where('status', 'resolved')
            ->whereNotNull('completed_at')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, completed_at)) as avg_time'))
            ->value('avg_time') ?? 0;

        $statusDistribution = [
            'open' => Ticket::whereBetween('created_at', [$currentMonth, $endOfMonth])->where('status', 'open')->count(),
            'onprogress' => Ticket::whereBetween('created_at', [$currentMonth, $endOfMonth])->where('status', 'onprogress')->count(),
            'resolved' => Ticket::whereBetween('created_at', [$currentMonth, $endOfMonth])->where('status', 'resolved')->count(),
            'rejected' => Ticket::whereBetween('created_at', [$currentMonth, $endOfMonth])->where('status', 'rejected')->count(),
        ];

        $dashboardData = [
            'total_tickets' => $totalTickets,
            'active_tickets' => $activeTickets,
            'resolved_tickets' => $resolvedTickets,
            'avg_resolution_time' => round($avgresolutionTime, 1),
            'status_distribution' => $statusDistribution,
        ];


        return response()->json([
            'message' => 'Dashboard statistics retrieved successfully',
            'data' => new DashboardResource($dashboardData),
        ], 200);
    }
}

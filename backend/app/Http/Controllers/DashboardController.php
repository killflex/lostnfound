<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Resources\DashboardResource;


class DashboardController extends Controller {
    public function getStatistics(){
        $totalTickets = Item::count();

        $activeTickets = Item::where('status', 'pending')
            ->count();

        $resolvedTickets = Item::where('status', 'resolved')
            ->count();

        $avgresolutionTime = Item::where('status', 'resolved')
            ->whereNotNull('updated_at')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_time'))
            ->value('avg_time') ?? 0;

        $statusDistribution = [
            'lost' => Item::where('type', 'lost')->count(),
            'found' => Item::where('type', 'found')->count(),
            'pending' => Item::where('status', 'pending')->count(),
            'resolved' => Item::where('status', 'resolved')->count(),
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

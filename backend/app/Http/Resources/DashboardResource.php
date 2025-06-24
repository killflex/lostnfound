<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'total_tickets' => $this['total_tickets'],
            'active_tickets' => $this['active_tickets'],
            'resolved_tickets' => $this['resolved_tickets'],
            'avg_resolution_time' => $this['avg_resolution_time'],
            'status_distribution' => [
                'lost' => $this['status_distribution']['lost'],
                'found' => $this['status_distribution']['found'],
                'pending' => $this['status_distribution']['pending'],
                'resolved' => $this['status_distribution']['resolved']
            ],
        ];
    }
}

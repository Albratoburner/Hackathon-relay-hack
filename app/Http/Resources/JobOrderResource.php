<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'job_id' => $this->job_id,
            'job_title' => $this->job_title,
            'job_description' => $this->job_description,
            'min_experience_years' => $this->min_experience_years,
            'preferred_location' => $this->preferred_location,
            'availability_type' => $this->availability_type,
            'max_results' => $this->max_results,
            'posted_date' => $this->posted_date?->format('Y-m-d'),
            'status' => $this->status,
            'recruiter_id' => $this->recruiter_id,
            'assignments_count' => $this->whenCounted('assignments'),
            'recent_rankings' => $this->whenLoaded('rankings', function () {
                return $this->rankings->take(5)->map(function ($ranking) {
                    return [
                        'ranking_id' => $ranking->ranking_id,
                        'execution_date' => $ranking->execution_date,
                        'candidates_returned' => $ranking->candidates_returned,
                    ];
                });
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

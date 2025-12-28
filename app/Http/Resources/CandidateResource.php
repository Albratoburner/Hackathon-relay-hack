<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'candidate_id' => $this->candidate_id,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'location' => $this->location,
            'availability_type' => $this->availability_type,
            'total_experience_years' => $this->total_experience_years,
            'bio' => $this->bio,
            'is_active' => $this->is_active,
            'skills' => CandidateSkillResource::collection($this->whenLoaded('skills')),
            'recent_assignments' => $this->whenLoaded('assignments', function () {
                return $this->assignments->take(5)->map(function ($assignment) {
                    return [
                        'assignment_id' => $assignment->assignment_id,
                        'job_id' => $assignment->job_id,
                        'start_date' => $assignment->start_date,
                        'end_date' => $assignment->end_date,
                        'status' => $assignment->status,
                    ];
                });
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

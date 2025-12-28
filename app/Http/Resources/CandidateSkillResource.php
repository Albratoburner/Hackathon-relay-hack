<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidateSkillResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'skill' => new SkillResource($this->whenLoaded('skill')),
            'proficiency_level' => $this->proficiency_level,
            'years_of_experience' => $this->years_of_experience,
            'level' => $this->whenLoaded('level', function () {
                return [
                    'level_id' => $this->level->level_id,
                    'level_name' => $this->level->level_name,
                ];
            }),
        ];
    }
}

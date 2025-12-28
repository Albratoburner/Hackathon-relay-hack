<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CandidateApiTest extends TestCase
{
    /**
     * Test candidate detail endpoint.
     */
    public function test_candidate_endpoint_returns_candidate_with_skills(): void
    {
        $response = $this->getJson('/api/candidates/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'candidate_id',
                    'full_name',
                    'email',
                    'skills' => [
                        '*' => [
                            'skill',
                            'proficiency_level',
                            'years_of_experience'
                        ]
                    ],
                    'recent_assignments'
                ]
            ]);
    }

    /**
     * Test candidate not found returns 404.
     */
    public function test_candidate_not_found_returns_404(): void
    {
        $response = $this->getJson('/api/candidates/99999');

        $response->assertStatus(404);
    }
}

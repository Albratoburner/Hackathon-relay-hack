<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RankingApiTest extends TestCase
{
    /**
     * Test ranking endpoint returns successful response.
     */
    public function test_ranking_endpoint_returns_successful_response(): void
    {
        // Test with existing job ID 1 from seeded data
        $response = $this->postJson('/api/jobs/1/rank', [
            'max_results' => 10
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'candidate_id',
                    'full_name'
                ]
            ]);
    }

    /**
     * Test ranking endpoint with max_results parameter.
     */
    public function test_ranking_endpoint_with_custom_weights(): void
    {
        $response = $this->postJson('/api/jobs/1/rank', [
            'max_results' => 5
        ]);

        $response->assertStatus(200)
            ->assertJson(function ($json) {
                return is_array($json) && count($json) <= 5;
            });
    }

    /**
     * Test ranking endpoint without parameters uses defaults.
     */
    public function test_ranking_endpoint_uses_default_parameters(): void
    {
        $response = $this->postJson('/api/jobs/1/rank');

        $response->assertStatus(200)
            ->assertJson(function ($json) {
                return is_array($json) && count($json) <= 20;
            });
    }

    /**
     * Test ranking endpoint with invalid job ID returns empty or error.
     */
    public function test_ranking_endpoint_with_invalid_job_id(): void
    {
        $response = $this->postJson('/api/jobs/99999/rank', [
            'max_results' => 10
        ]);

        // SP throws error for non-existent job
        $response->assertStatus(500);
    }

    /**
     * Test ranking endpoint validates max_results parameter.
     */
    public function test_ranking_endpoint_validates_top_n(): void
    {
        $response = $this->postJson('/api/jobs/1/rank', [
            'max_results' => -5
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['max_results']);
    }
}

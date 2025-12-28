<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class JobOrderApiTest extends TestCase
{
    /**
     * Test jobs list endpoint.
     */
    public function test_jobs_list_endpoint_returns_paginated_response(): void
    {
        $response = $this->getJson('/api/jobs');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'job_id',
                        'job_title',
                        'job_description',
                        'status',
                        'posted_date'
                    ]
                ],
                'links',
                'meta'
            ]);
    }

    /**
     * Test single job endpoint.
     */
    public function test_single_job_endpoint_returns_job_details(): void
    {
        $response = $this->getJson('/api/jobs/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'job_id',
                    'job_title',
                    'job_description',
                    'status',
                    'recent_rankings'
                ]
            ]);
    }

    /**
     * Test job not found returns 404.
     */
    public function test_job_not_found_returns_404(): void
    {
        $response = $this->getJson('/api/jobs/99999');

        $response->assertStatus(404);
    }
}

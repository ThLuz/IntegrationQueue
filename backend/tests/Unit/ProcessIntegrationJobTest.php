<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\Integration\Models\IntegrationJob;
use App\Jobs\ProcessIntegrationJob;

class ProcessIntegrationJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_success()
    {
        $jobModel = IntegrationJob::create([
            'external_id' => '200', // par → SUCCESS
            'payload' => ['cpf' => '12345678901'],
            'status' => 'PENDING',
            'attempts' => 0
        ]);

        ProcessIntegrationJob::dispatchSync($jobModel->id);

        $this->assertDatabaseHas('integration_jobs', [
            'id' => $jobModel->id,
            'status' => 'SUCCESS'
        ]);
    }

    public function test_job_error()
    {
        $jobModel = IntegrationJob::create([
            'external_id' => '201', // ímpar → ERROR
            'payload' => ['cpf' => '12345678901'],
            'status' => 'PENDING',
            'attempts' => 0
        ]);

        try {
            ProcessIntegrationJob::dispatchSync($jobModel->id);
        } catch (\Throwable $e) {
            // esperado para simular retry
        }

        $this->assertDatabaseHas('integration_jobs', [
            'id' => $jobModel->id,
            'status' => 'ERROR',
            'attempts' => 1
        ]);
    }
}

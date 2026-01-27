<?php

namespace App\Jobs;

use App\Domain\Integration\Models\IntegrationJob;
use App\Domain\Integration\Enums\IntegrationStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessIntegrationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function backoff(): array
    {
        return [10, 30, 60];
    }    

    public function __construct(private int $jobId) {}

    public function handle(): void
    {
        $job = IntegrationJob::findOrFail($this->jobId);

        Log::info("Iniciando processamento do job {$job->id}");

        try {
            $job->update([
                'status' => IntegrationStatus::PROCESSING->value,
            ]);

            // Validações básicas
            if (empty($job->external_id)) {
                throw new \Exception('external_id é obrigatório');
            }

            if (!preg_match('/^\d{11}$/', $job->payload['cpf'] ?? '')) {
                throw new \Exception('CPF inválido');
            }

            // Chamada para API fake
            $response = Http::post(url('http://nginx/api/fake/external/customers'), [
                'external_id' => $job->external_id,
            ]);

            if ($response->failed()) {
                throw new \Exception('Erro ao integrar com sistema externo');
            }

            // Sucesso
            $job->update([
                'status' => IntegrationStatus::SUCCESS->value,
                'processed_at' => now(),
            ]);

            Log::info("Job {$job->id} processado com sucesso");
        } catch (Throwable $e) {
            $job->increment('attempts');

            $job->update([
                'status' => IntegrationStatus::ERROR->value,
                'last_error' => $e->getMessage(),
            ]);

            Log::error("Erro no job {$job->id}: " . $e->getMessage());

            throw $e; // permite retry automático
        }
    }
}

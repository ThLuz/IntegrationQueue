<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use App\Domain\Integration\Models\IntegrationJob;

class IntegrationApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa criação de job com sucesso
     */
    public function test_create_job_success()
    {
        // Impede execução real do job durante o teste
        Queue::fake();

        $payload = [
            'external_id' => '200',
            'nome' => 'Cliente Teste',
            'cpf' => '12345678901',
            'email' => 'teste@email.com'
        ];

        $response = $this->postJson('/api/integrations/customers', $payload);

        $response->assertStatus(202)
                 ->assertJsonStructure(['id', 'status']);

        // Verifica se o job foi criado no banco com status PENDING
        $this->assertDatabaseHas('integration_jobs', [
            'external_id' => '200',
            'status' => 'PENDING',
        ]);

        // Checa se o job foi enviado para a fila
        Queue::assertPushed(\App\Jobs\ProcessIntegrationJob::class);
    }

    /**
     * Testa criação de job com dados inválidos (validação)
     */
    public function test_create_job_validation_error()
    {
        $payload = [];

        $response = $this->postJson('/api/integrations/customers', $payload);

        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'errors' => ['external_id', 'nome', 'cpf', 'email']
                 ]);
    }
}

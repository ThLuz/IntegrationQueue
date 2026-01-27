<?php

namespace App\Domain\Integration\Services;

use App\Domain\Integration\DTOs\CreateIntegrationDTO;
use App\Domain\Integration\Repositories\IntegrationJobRepository;
use App\Domain\Integration\Enums\IntegrationStatus;
use App\Jobs\ProcessIntegrationJob;

class CreateIntegrationService
{
    public function __construct(
        private IntegrationJobRepository $repository
    ) {}

    public function execute(CreateIntegrationDTO $dto)
    {
        $job = $this->repository->create([
            'external_id' => $dto->externalId,
            'payload' => $dto->payload,
            'status' => IntegrationStatus::PENDING->value,
            'attempts' => 0
        ]);

        ProcessIntegrationJob::dispatch($job->id);

        return $job;
    }
}

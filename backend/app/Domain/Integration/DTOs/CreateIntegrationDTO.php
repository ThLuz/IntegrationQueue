<?php

namespace App\Domain\Integration\DTOs;

class CreateIntegrationDTO
{
    public function __construct(
        public string $externalId,
        public array $payload
    ) {}
}

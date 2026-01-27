<?php

namespace App\Domain\Integration\Repositories;

use App\Domain\Integration\Models\IntegrationJob;

class IntegrationJobRepository
{
    public function create(array $data): IntegrationJob
    {
        return IntegrationJob::create($data);
    }

    public function findById(int $id): ?IntegrationJob
    {
        return IntegrationJob::find($id);
    }

    public function list(array $filters = [])
    {
        $query = IntegrationJob::query()->orderByDesc('id');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['external_id'])) {
            $query->where('external_id', $filters['external_id']);
        }

        return $query->limit(20)->get();
    }
}

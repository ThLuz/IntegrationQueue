<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateIntegrationRequest;
use App\Http\Resources\IntegrationResource;
use App\Domain\Integration\Services\CreateIntegrationService;
use App\Domain\Integration\Repositories\IntegrationJobRepository;
use App\Domain\Integration\DTOs\CreateIntegrationDTO;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    public function store(CreateIntegrationRequest $request, CreateIntegrationService $service)
    {
        $dto = new CreateIntegrationDTO(
            $request->external_id,
            $request->all()
        );

        $job = $service->execute($dto);

        return response()->json([
            'id' => $job->id,
            'status' => $job->status
        ], 202);
    }

    public function show(int $id, IntegrationJobRepository $repository)
    {
        $job = $repository->findById($id);

        if (!$job) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return new IntegrationResource($job);
    }

    public function index(Request $request, IntegrationJobRepository $repository)
    {
        $jobs = $repository->list($request->only('status', 'external_id'));

        return IntegrationResource::collection($jobs);
    }
}

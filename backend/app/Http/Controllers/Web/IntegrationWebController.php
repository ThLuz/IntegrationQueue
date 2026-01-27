<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Domain\Integration\Repositories\IntegrationJobRepository;
use Illuminate\Http\Request;

class IntegrationWebController extends Controller
{
    public function index(Request $request, IntegrationJobRepository $repository)
    {
        $filters = $request->only('status', 'external_id');
        $jobs = $repository->list($filters);

        return view('integrations.index', compact('jobs'));
    }

}

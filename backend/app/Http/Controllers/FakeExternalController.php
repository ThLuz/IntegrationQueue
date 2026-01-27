<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FakeExternalController extends Controller
{
    public function store(Request $request)
    {
        $externalId = (int) $request->external_id;

        if ($externalId % 2 === 0) {
            return response()->json(['message' => 'Sucesso'], 200);
        }

        return response()->json(['message' => 'Erro externo'], 500);
    }
}

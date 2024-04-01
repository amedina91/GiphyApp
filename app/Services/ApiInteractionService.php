<?php

namespace App\Services;

use App\Models\ApiInteraction;
use Illuminate\Http\Request;

class ApiInteractionService
{
    public function logInteraction(Request $request, $response)
    {
        ApiInteraction::create([
            'user_id' => auth()->user()->id,
            'service' => $request->path(),
            'request_body' => json_encode($request->all()),
            'response_code' => $response->status(),
            'response_body' => $response->getContent(),
            'ip_address' => $request->ip()
        ]);
    }
}

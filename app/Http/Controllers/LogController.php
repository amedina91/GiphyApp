<?php

namespace App\Http\Controllers;

use App\Models\ApiInteraction;

class LogController extends Controller
{

    public function index()
    {
        $logs = ApiInteraction::all();

        return response()->json($logs);
    }
}

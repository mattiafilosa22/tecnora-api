<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class HealthController extends Controller
{
    /**
     * Report application status and database reachability.
     */
    public function __invoke(): JsonResponse
    {
        try {
            DB::connection()->getPdo();
            $db = true;
        } catch (Throwable) {
            $db = false;
        }

        return response()->json(['status' => 'ok', 'db' => $db]);
    }
}

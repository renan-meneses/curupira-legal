<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

final class HealthController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $checks = ['app' => 'ok', 'database' => 'ok', 'cache' => 'ok'];
        $healthy = true;

        try { DB::select('select 1'); } catch (\Throwable) { $checks['database'] = 'error'; $healthy = false; }
        try { Cache::store('array')->put('healthcheck', true, 5); } catch (\Throwable) { $checks['cache'] = 'error'; $healthy = false; }

        return response()->json(['status' => $healthy ? 'ok' : 'degraded', 'checks' => $checks], $healthy ? 200 : 503);
    }
}

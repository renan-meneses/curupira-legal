<?php

declare(strict_types=1);

use App\Http\Middleware\EnsureOrganizationContext;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'organization.context' => EnsureOrganizationContext::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {})
    ->create();

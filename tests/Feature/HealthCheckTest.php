<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns healthy status when database and cache are available', function (): void {
    $this->get('/healthz')->assertOk()->assertJsonPath('status', 'ok')->assertHeader('X-Request-Id');
});

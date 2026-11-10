<?php

declare(strict_types=1);

use App\Models\Organization;
use App\Models\OrganizationMembership;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('exports only the active organization receivables as CSV', function (): void {
    $user = User::query()->create(['name' => 'Relatórios', 'email' => 'relatorios@example.com', 'password' => 'password-password']);
    $organization = Organization::query()->create(['name' => 'Empresa Relatórios', 'slug' => 'empresa-relatorios']);
    OrganizationMembership::query()->create(['organization_id' => $organization->getKey(), 'user_id' => $user->getKey(), 'role' => 'financial_manager', 'permissions' => [], 'joined_at' => now()]);
    Sanctum::actingAs($user);

    $response = $this->withHeader('X-Organization-Id', $organization->getKey())->get('/api/v1/reports/receivables.csv?month=2026-11');

    $response->assertOk()->assertHeader('content-type', 'text/csv; charset=UTF-8');

    $this->withHeader('X-Organization-Id', $organization->getKey())->get('/api/v1/reports/contracts.csv')->assertOk()->assertHeader('content-type', 'text/csv; charset=UTF-8');
});

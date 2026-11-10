<?php

declare(strict_types=1);

use App\Models\Organization;
use App\Models\OrganizationMembership;
use App\Models\TaxConfiguration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('reviews a tax configuration and creates a dated preview snapshot', function (): void {
    $user = User::query()->create(['name' => 'Contador', 'email' => 'contador@example.com', 'password' => 'password-password']);
    $organization = Organization::query()->create(['name' => 'Empresa Fiscal', 'slug' => 'empresa-fiscal']);
    OrganizationMembership::query()->create(['organization_id' => $organization->getKey(), 'user_id' => $user->getKey(), 'role' => 'accountant', 'permissions' => [], 'joined_at' => now()]);
    Sanctum::actingAs($user);
    $headers = ['X-Organization-Id' => $organization->getKey()];

    $configuration = $this->withHeaders($headers)->postJson('/api/v1/tax/configurations', [
        'service_code' => '0101',
        'effective_from' => '2026-01-01',
        'rules' => [['code' => 'ISS', 'basis_points' => 500, 'treatment' => 'withheld']],
    ])->assertCreated()->json('data.id');
    $this->withHeaders($headers)->patchJson("/api/v1/tax/configurations/{$configuration}/review", ['status' => 'active'])->assertOk();

    $preview = $this->withHeaders($headers)->postJson('/api/v1/tax/preview', ['service_code' => '0101', 'competency_date' => '2026-11-10', 'base_cents' => 100000])->assertOk();

    $preview->assertJsonPath('data.result_snapshot.withheld_cents', 5000);
    expect($preview->json('data.configuration.id'))->toBe($configuration);
});

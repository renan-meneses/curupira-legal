<?php

declare(strict_types=1);

use App\Models\Contract;
use App\Models\Customer;
use App\Models\Organization;
use App\Models\OrganizationMembership;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('returns dashboard metrics scoped to the active organization', function (): void {
    $user = User::query()->create(['name' => 'Dashboard', 'email' => 'dashboard@example.com', 'password' => 'password-password']);
    $organization = Organization::query()->create(['name' => 'Empresa Dashboard', 'slug' => 'empresa-dashboard']);
    $other = Organization::query()->create(['name' => 'Outra Empresa', 'slug' => 'outra-empresa']);
    OrganizationMembership::query()->create(['organization_id' => $organization->getKey(), 'user_id' => $user->getKey(), 'role' => 'organization_administrator', 'permissions' => [], 'joined_at' => now()]);
    $customer = Customer::query()->create(['organization_id' => $organization->getKey(), 'name' => 'Cliente Dashboard', 'document' => '52998224725']);
    Contract::query()->create(['organization_id' => $organization->getKey(), 'customer_id' => $customer->getKey(), 'title' => 'Ativo', 'contract_type' => 'indefinite', 'billing_model' => 'fixed', 'status' => 'active', 'starts_on' => '2026-01-01']);
    Contract::query()->create(['organization_id' => $other->getKey(), 'customer_id' => Customer::query()->create(['organization_id' => $other->getKey(), 'name' => 'Outro', 'document' => '04252011000110'])->getKey(), 'title' => 'Fora do tenant', 'contract_type' => 'indefinite', 'billing_model' => 'fixed', 'status' => 'active', 'starts_on' => '2026-01-01']);
    Sanctum::actingAs($user);

    $response = $this->withHeader('X-Organization-Id', $organization->getKey())->getJson('/api/v1/dashboard?month=2026-11');

    $response->assertOk()->assertJsonPath('data.metrics.active_contracts', 1)->assertJsonPath('data.period.starts_on', '2026-11-01');
});

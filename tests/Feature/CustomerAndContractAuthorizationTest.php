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

it('creates a customer in the active organization', function (): void {
    $user = User::query()->create(['name' => 'Operador', 'email' => 'operador@example.com', 'password' => 'password-password']);
    $organization = Organization::query()->create(['name' => 'Empresa A', 'slug' => 'empresa-a']);
    OrganizationMembership::query()->create(['organization_id' => $organization->getKey(), 'user_id' => $user->getKey(), 'role' => 'organization_administrator', 'permissions' => [], 'joined_at' => now()]);
    Sanctum::actingAs($user);

    $response = $this->withHeader('X-Organization-Id', $organization->getKey())->postJson('/api/v1/customers', [
        'name' => 'Cliente Novo',
        'document' => '529.982.247-25',
        'email' => 'cliente@example.com',
    ]);

    $response->assertCreated()->assertJsonPath('data.document', '52998224725');
    expect(Customer::query()->where('organization_id', $organization->getKey())->count())->toBe(1);
});

it('blocks a contract linked to a customer from another organization', function (): void {
    $user = User::query()->create(['name' => 'Operador', 'email' => 'operador2@example.com', 'password' => 'password-password']);
    $organization = Organization::query()->create(['name' => 'Empresa A', 'slug' => 'empresa-b']);
    $otherOrganization = Organization::query()->create(['name' => 'Empresa B', 'slug' => 'empresa-c']);
    OrganizationMembership::query()->create(['organization_id' => $organization->getKey(), 'user_id' => $user->getKey(), 'role' => 'organization_administrator', 'permissions' => [], 'joined_at' => now()]);
    $customer = Customer::query()->create(['organization_id' => $otherOrganization->getKey(), 'name' => 'Cliente isolado', 'document' => '04252011000110']);
    Sanctum::actingAs($user);

    $response = $this->withHeader('X-Organization-Id', $organization->getKey())->postJson('/api/v1/contracts', [
        'customer_id' => $customer->getKey(),
        'title' => 'Contrato indevido',
        'contract_type' => 'fixed_term',
        'billing_model' => 'hourly',
        'starts_on' => '2026-11-01',
        'hourly_rate' => '100.00',
    ]);

    $response->assertNotFound();
    expect(Contract::query()->count())->toBe(0);
});

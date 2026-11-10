<?php

declare(strict_types=1);

use App\Models\Customer;
use App\Models\Organization;
use App\Models\OrganizationMembership;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('does not expose customers from another organization', function (): void {
    $user = User::query()->create(['name' => 'Teste', 'email' => 'teste@example.com', 'password' => 'password-password']);
    $visibleOrganization = Organization::query()->create(['name' => 'Organização visível', 'slug' => 'organizacao-visivel']);
    $hiddenOrganization = Organization::query()->create(['name' => 'Organização secreta', 'slug' => 'organizacao-secreta']);
    OrganizationMembership::query()->create(['organization_id' => $visibleOrganization->getKey(), 'user_id' => $user->getKey(), 'role' => 'organization_administrator', 'permissions' => [], 'joined_at' => now()]);
    Customer::query()->create(['organization_id' => $visibleOrganization->getKey(), 'name' => 'Cliente visível', 'document' => '52998224725']);
    Customer::query()->create(['organization_id' => $hiddenOrganization->getKey(), 'name' => 'Cliente secreto', 'document' => '04252011000110']);

    Sanctum::actingAs($user);
    $response = $this->withHeader('X-Organization-Id', $visibleOrganization->getKey())->getJson('/api/v1/customers');

    $response->assertOk()->assertJsonFragment(['name' => 'Cliente visível'])->assertJsonMissing(['name' => 'Cliente secreto']);
});

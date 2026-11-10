<?php

declare(strict_types=1);

use App\Models\Organization;
use App\Models\OrganizationMembership;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('rejects unauthenticated access to tenant routes', function (): void {
    $this->getJson('/api/v1/customers')->assertUnauthorized();
});

it('rejects an authenticated request without an organization context', function (): void {
    $user = User::query()->create(['name' => 'Sem contexto', 'email' => 'sem-contexto@example.com', 'password' => 'password-password']);
    Sanctum::actingAs($user);

    $this->getJson('/api/v1/customers')->assertForbidden();
});

it('rejects a user using an organization where they have no membership', function (): void {
    $user = User::query()->create(['name' => 'Membro', 'email' => 'membro@example.com', 'password' => 'password-password']);
    $organization = Organization::query()->create(['name' => 'Privada', 'slug' => 'privada']);
    Sanctum::actingAs($user);

    $this->withHeader('X-Organization-Id', $organization->getKey())->getJson('/api/v1/customers')->assertForbidden();
});

it('rejects switching to an organization without membership', function (): void {
    $user = User::query()->create(['name' => 'Troca', 'email' => 'troca@example.com', 'password' => 'password-password']);
    $organization = Organization::query()->create(['name' => 'Não autorizada', 'slug' => 'nao-autorizada']);
    Sanctum::actingAs($user);

    $this->postJson("/api/v1/organizations/{$organization->getKey()}/switch")->assertForbidden();
});

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

it('runs an approved time entry through billing and partial payment', function (): void {
    $user = User::query()->create(['name' => 'Financeiro', 'email' => 'financeiro@example.com', 'password' => 'password-password']);
    $organization = Organization::query()->create(['name' => 'Empresa Financeira', 'slug' => 'empresa-financeira']);
    OrganizationMembership::query()->create(['organization_id' => $organization->getKey(), 'user_id' => $user->getKey(), 'role' => 'financial_manager', 'permissions' => [], 'joined_at' => now()]);
    $customer = Customer::query()->create(['organization_id' => $organization->getKey(), 'name' => 'Cliente Billing', 'document' => '52998224725']);
    $contract = Contract::query()->create(['organization_id' => $organization->getKey(), 'customer_id' => $customer->getKey(), 'title' => 'Contrato horário', 'contract_type' => 'indefinite', 'billing_model' => 'hourly', 'starts_on' => '2026-11-01', 'hourly_rate' => '100.00']);
    Sanctum::actingAs($user);
    $headers = ['X-Organization-Id' => $organization->getKey()];

    $entry = $this->withHeaders($headers)->postJson('/api/v1/time-entries', ['contract_id' => $contract->getKey(), 'worked_on' => '2026-11-10', 'duration_minutes' => 90, 'billable' => true])->assertCreated()->json('data.id');
    $this->withHeaders($headers)->patchJson("/api/v1/time-entries/{$entry}/submit")->assertOk();
    $this->withHeaders($headers)->patchJson("/api/v1/time-entries/{$entry}/review", ['status' => 'approved'])->assertOk();

    $billing = $this->withHeaders($headers)->postJson('/api/v1/billing/hourly', ['contract_id' => $contract->getKey(), 'starts_on' => '2026-11-01', 'ends_on' => '2026-11-30'])->assertCreated();
    expect($billing->json('data.gross_cents'))->toBe(15000);
    $period = $billing->json('data.period.id');
    $record = $billing->json('data.id');
    $this->withHeaders($headers)->postJson("/api/v1/billing/periods/{$period}/finalize")->assertOk();

    $receivable = $this->withHeaders($headers)->postJson('/api/v1/receivables', ['billing_record_id' => $record, 'due_on' => '2026-12-10'])->assertCreated()->json('data.id');
    $this->withHeaders($headers)->postJson("/api/v1/receivables/{$receivable}/payments", ['amount_cents' => 5000, 'paid_on' => '2026-12-05', 'method' => 'pix'])->assertCreated();

    $this->withHeaders($headers)->getJson('/api/v1/receivables')->assertOk()->assertJsonFragment(['paid_cents' => 5000])->assertJsonFragment(['status' => 'partial']);
});

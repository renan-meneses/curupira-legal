<?php

declare(strict_types=1);

use App\Domain\Billing\BillingPeriodStatus;
use App\Domain\Finance\ReceivableStatus;
use App\Jobs\MarkOverdueReceivables;
use App\Jobs\NotifyExpiringContracts;
use App\Jobs\ReconcilePendingFiscalInvoices;
use App\Models\BillingPeriod;
use App\Models\BillingRecord;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\FiscalInvoice;
use App\Models\Organization;
use App\Models\OrganizationMembership;
use App\Models\Receivable;
use App\Models\User;
use App\Domain\Fiscal\FiscalInvoiceStatus;
use App\Domain\Organizations\OrganizationContext;
use App\Application\Actions\ReconcileFiscalInvoice;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('marks an open overdue receivable without touching paid records', function (): void {
    CarbonImmutable::setTestNow('2026-11-10 10:00:00');
    $organization = Organization::query()->create(['name' => 'Automação Financeira', 'slug' => 'automacao-financeira']);
    $customer = Customer::query()->create(['organization_id' => $organization->getKey(), 'name' => 'Cliente', 'document' => '52998224725']);
    $contract = Contract::query()->create(['organization_id' => $organization->getKey(), 'customer_id' => $customer->getKey(), 'title' => 'Contrato', 'contract_type' => 'indefinite', 'billing_model' => 'fixed', 'starts_on' => '2026-01-01']);
    $period = BillingPeriod::query()->create(['organization_id' => $organization->getKey(), 'starts_on' => '2026-10-01', 'ends_on' => '2026-10-31', 'status' => BillingPeriodStatus::Closed]);
    $record = BillingRecord::query()->create(['organization_id' => $organization->getKey(), 'billing_period_id' => $period->getKey(), 'contract_id' => $contract->getKey(), 'idempotency_key' => 'automation-1', 'billing_model' => 'fixed', 'gross_cents' => 10000, 'calculation_snapshot' => [], 'status' => 'finalized', 'finalized_at' => now()]);
    $receivable = Receivable::query()->create(['organization_id' => $organization->getKey(), 'billing_record_id' => $record->getKey(), 'customer_id' => $customer->getKey(), 'due_on' => '2026-11-09', 'amount_cents' => 10000, 'paid_cents' => 0, 'status' => ReceivableStatus::Open]);

    (new MarkOverdueReceivables())->handle();

    expect($receivable->fresh()->status)->toBe(ReceivableStatus::Overdue);
});

it('deduplicates contract expiration notifications', function (): void {
    CarbonImmutable::setTestNow('2026-11-10 10:00:00');
    $user = User::query()->create(['name' => 'Avisos', 'email' => 'avisos@example.com', 'password' => 'password-password']);
    $organization = Organization::query()->create(['name' => 'Avisos', 'slug' => 'avisos']);
    OrganizationMembership::query()->create(['organization_id' => $organization->getKey(), 'user_id' => $user->getKey(), 'role' => 'organization_administrator', 'permissions' => [], 'joined_at' => now()]);
    $customer = Customer::query()->create(['organization_id' => $organization->getKey(), 'name' => 'Cliente Aviso', 'document' => '04252011000110']);
    Contract::query()->create(['organization_id' => $organization->getKey(), 'customer_id' => $customer->getKey(), 'title' => 'Vencendo', 'contract_type' => 'fixed_term', 'billing_model' => 'fixed', 'status' => 'active', 'starts_on' => '2026-01-01', 'ends_on' => '2026-11-20']);

    (new NotifyExpiringContracts())->handle();
    (new NotifyExpiringContracts())->handle();

    expect($user->notifications()->count())->toBe(1);
});

it('reconciles pending fiscal invoices and records an event', function (): void {
    $organization = Organization::query()->create(['name' => 'Fiscal Job', 'slug' => 'fiscal-job']);
    FiscalInvoice::query()->create(['organization_id' => $organization->getKey(), 'provider' => 'mock', 'status' => FiscalInvoiceStatus::Processing, 'protocol' => 'MOCK-123', 'dps_payload' => []]);
    $context = app(OrganizationContext::class);

    (new ReconcilePendingFiscalInvoices())->handle(app(ReconcileFiscalInvoice::class), $context);

    expect(FiscalInvoice::query()->first()->events()->count())->toBe(1);
});

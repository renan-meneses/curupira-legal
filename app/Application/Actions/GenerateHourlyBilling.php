<?php

declare(strict_types=1);

namespace App\Application\Actions;

use App\Domain\Billing\BillingEngine;
use App\Domain\Billing\Money;
use App\Domain\Billing\BillingPeriodStatus;
use App\Domain\Organizations\OrganizationContext;
use App\Models\BillingPeriod;
use App\Models\BillingRecord;
use App\Models\Contract;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

final class GenerateHourlyBilling
{
    public function __construct(private readonly BillingEngine $engine) {}

    public function execute(Contract $contract, CarbonImmutable $startsOn, CarbonImmutable $endsOn, OrganizationContext $context): BillingRecord
    {
        return DB::transaction(function () use ($contract, $startsOn, $endsOn, $context): BillingRecord {
            $lockedContract = Contract::query()->where('organization_id', $context->id())->whereKey($contract->getKey())->lockForUpdate()->firstOrFail();
            $period = BillingPeriod::query()->firstOrCreate(['organization_id' => $context->id(), 'starts_on' => $startsOn->toDateString(), 'ends_on' => $endsOn->toDateString()]);
            $periodStatus = $period->status instanceof BillingPeriodStatus ? $period->status : BillingPeriodStatus::from((string) $period->status);
            if ($periodStatus !== BillingPeriodStatus::Open) {
                throw new \DomainException('Não é possível gerar billing para um período fechado.');
            }
            $key = sprintf('hourly:%s:%s:%s', $lockedContract->getKey(), $startsOn->toDateString(), $endsOn->toDateString());
            $existing = BillingRecord::query()->where('organization_id', $context->id())->where('idempotency_key', $key)->first();
            if ($existing !== null) {
                return $existing;
            }

            $snapshot = $this->engine->calculateHourly($lockedContract, $startsOn, $endsOn);
            return BillingRecord::query()->create([
                'organization_id' => $context->id(),
                'billing_period_id' => $period->getKey(),
                'contract_id' => $lockedContract->getKey(),
                'idempotency_key' => $key,
                'billing_model' => 'hourly',
                'gross_cents' => Money::fromDecimal($snapshot['gross'])->cents,
                'calculation_snapshot' => $snapshot,
            ]);
        });
    }
}

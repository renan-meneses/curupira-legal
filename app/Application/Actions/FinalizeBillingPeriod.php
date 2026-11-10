<?php

declare(strict_types=1);

namespace App\Application\Actions;

use App\Domain\Billing\BillingPeriodLifecycle;
use App\Domain\Billing\BillingPeriodStatus;
use App\Domain\Organizations\OrganizationContext;
use App\Models\BillingPeriod;
use Illuminate\Support\Facades\DB;

final class FinalizeBillingPeriod
{
    public function __construct(private readonly BillingPeriodLifecycle $lifecycle) {}

    public function execute(BillingPeriod $period, OrganizationContext $context): BillingPeriod
    {
        return DB::transaction(function () use ($period, $context): BillingPeriod {
            $locked = BillingPeriod::query()->where('organization_id', $context->id())->whereKey($period->getKey())->lockForUpdate()->firstOrFail();
            $status = $locked->status instanceof BillingPeriodStatus ? $locked->status : BillingPeriodStatus::from((string) $locked->status);
            $this->lifecycle->assertCanClose($status);
            $locked->status = BillingPeriodStatus::Closed;
            $locked->save();
            $locked->records()->where('status', 'draft')->update(['status' => 'finalized', 'finalized_at' => now()]);

            return $locked->fresh('records');
        });
    }
}

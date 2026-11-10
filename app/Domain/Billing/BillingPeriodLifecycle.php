<?php

declare(strict_types=1);

namespace App\Domain\Billing;

use DomainException;

final class BillingPeriodLifecycle
{
    public function assertCanClose(BillingPeriodStatus $status): void
    {
        if ($status !== BillingPeriodStatus::Open) {
            throw new DomainException('O período de billing já está fechado.');
        }
    }
}

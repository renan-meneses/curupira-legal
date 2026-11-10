<?php

declare(strict_types=1);

namespace App\Domain\Billing;

enum BillingPeriodStatus: string
{
    case Open = 'open';
    case Closed = 'closed';
}

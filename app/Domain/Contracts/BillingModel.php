<?php

declare(strict_types=1);

namespace App\Domain\Contracts;

enum BillingModel: string
{
    case Hourly = 'hourly';
    case Fixed = 'fixed';
    case Hybrid = 'hybrid';
    case Milestone = 'milestone';
    case Recurring = 'recurring';
}

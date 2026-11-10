<?php

declare(strict_types=1);

namespace App\Domain\Contracts;

enum ContractStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Suspended = 'suspended';
    case Expired = 'expired';
    case Terminated = 'terminated';
    case Renewed = 'renewed';
}

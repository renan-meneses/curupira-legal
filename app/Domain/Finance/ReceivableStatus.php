<?php

declare(strict_types=1);

namespace App\Domain\Finance;

enum ReceivableStatus: string
{
    case Open = 'open';
    case Partial = 'partial';
    case Paid = 'paid';
    case Overdue = 'overdue';
}

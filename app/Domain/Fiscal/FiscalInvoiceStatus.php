<?php

declare(strict_types=1);

namespace App\Domain\Fiscal;

enum FiscalInvoiceStatus: string
{
    case Draft = 'draft';
    case Processing = 'processing';
    case Authorized = 'authorized';
    case Rejected = 'rejected';
    case Cancelled = 'cancelled';
}

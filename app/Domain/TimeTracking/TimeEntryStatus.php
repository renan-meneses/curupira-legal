<?php

declare(strict_types=1);

namespace App\Domain\TimeTracking;

enum TimeEntryStatus: string
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case Approved = 'approved';
    case Rejected = 'rejected';
}

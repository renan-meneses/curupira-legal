<?php

declare(strict_types=1);

namespace App\Domain\TimeTracking;

use DomainException;

final class TimeEntryLifecycle
{
    public function assertCanTransition(TimeEntryStatus $from, TimeEntryStatus $to): void
    {
        $allowed = match ($from) {
            TimeEntryStatus::Draft => [TimeEntryStatus::Submitted],
            TimeEntryStatus::Submitted => [TimeEntryStatus::Approved, TimeEntryStatus::Rejected],
            TimeEntryStatus::Rejected => [TimeEntryStatus::Draft, TimeEntryStatus::Submitted],
            TimeEntryStatus::Approved => [],
        };

        if (!in_array($to, $allowed, true)) {
            throw new DomainException("Não é possível alterar apontamento de {$from->value} para {$to->value}.");
        }
    }
}

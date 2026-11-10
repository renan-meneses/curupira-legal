<?php

declare(strict_types=1);

namespace App\Domain\Billing;

use App\Models\Contract;
use App\Models\TimeEntry;
use Carbon\CarbonImmutable;

final class BillingEngine
{
    /** @return array{minutes: int, rate: string, gross: string, entries: list<string>} */
    public function calculateHourly(Contract $contract, CarbonImmutable $startsOn, CarbonImmutable $endsOn): array
    {
        $rate = Money::fromDecimal((string) $contract->hourly_rate);
        $entries = TimeEntry::query()
            ->where('organization_id', $contract->organization_id)
            ->where('contract_id', $contract->getKey())
            ->where('status', 'approved')
            ->where('billable', true)
            ->whereBetween('worked_on', [$startsOn->toDateString(), $endsOn->toDateString()])
            ->orderBy('worked_on')
            ->get(['id', 'worked_on', 'duration_minutes']);

        $minutes = (int) $entries->sum('duration_minutes');

        return [
            'minutes' => $minutes,
            'rate' => $rate->decimal(),
            'gross' => $rate->hourlyForMinutes($minutes)->decimal(),
            'entries' => $entries->pluck('id')->values()->all(),
        ];
    }
}

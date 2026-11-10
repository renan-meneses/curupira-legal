<?php

declare(strict_types=1);

namespace App\Application\Actions;

use App\Domain\Organizations\OrganizationContext;
use App\Domain\TimeTracking\TimeEntryLifecycle;
use App\Domain\TimeTracking\TimeEntryStatus;
use App\Models\TimeEntry;
use Illuminate\Support\Facades\DB;

final class SubmitTimeEntry
{
    public function __construct(private readonly TimeEntryLifecycle $lifecycle) {}

    public function execute(TimeEntry $entry, OrganizationContext $context): TimeEntry
    {
        return DB::transaction(function () use ($entry, $context): TimeEntry {
            $locked = TimeEntry::query()->where('organization_id', $context->id())->whereKey($entry->getKey())->lockForUpdate()->firstOrFail();
            $current = $locked->status instanceof TimeEntryStatus ? $locked->status : TimeEntryStatus::from((string) $locked->status);
            $this->lifecycle->assertCanTransition($current, TimeEntryStatus::Submitted);
            $locked->status = TimeEntryStatus::Submitted;
            $locked->save();
            return $locked->fresh(['contract', 'user']);
        });
    }
}

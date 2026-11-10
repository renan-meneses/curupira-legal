<?php

declare(strict_types=1);

namespace App\Application\Actions;

use App\Domain\Organizations\OrganizationContext;
use App\Domain\TimeTracking\TimeEntryLifecycle;
use App\Domain\TimeTracking\TimeEntryStatus;
use App\Models\TimeEntry;
use Illuminate\Support\Facades\DB;

final class ReviewTimeEntry
{
    public function __construct(private readonly TimeEntryLifecycle $lifecycle) {}

    public function execute(TimeEntry $entry, TimeEntryStatus $target, ?string $reason, OrganizationContext $context, string $reviewerId): TimeEntry
    {
        return DB::transaction(function () use ($entry, $target, $reason, $context, $reviewerId): TimeEntry {
            $locked = TimeEntry::query()->where('organization_id', $context->id())->whereKey($entry->getKey())->lockForUpdate()->firstOrFail();
            $current = $locked->status instanceof TimeEntryStatus ? $locked->status : TimeEntryStatus::from((string) $locked->status);
            $this->lifecycle->assertCanTransition($current, $target);
            $locked->status = $target;
            $locked->rejection_reason = $target === TimeEntryStatus::Rejected ? $reason : null;
            $locked->approved_by = $target === TimeEntryStatus::Approved ? $reviewerId : null;
            $locked->approved_at = $target === TimeEntryStatus::Approved ? now() : null;
            $locked->save();

            return $locked->fresh(['contract', 'user']);
        });
    }
}

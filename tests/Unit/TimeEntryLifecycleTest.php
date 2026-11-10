<?php

declare(strict_types=1);

use App\Domain\TimeTracking\TimeEntryLifecycle;
use App\Domain\TimeTracking\TimeEntryStatus;

it('allows submitted entries to be approved or rejected', function (): void {
    $lifecycle = new TimeEntryLifecycle();
    $lifecycle->assertCanTransition(TimeEntryStatus::Submitted, TimeEntryStatus::Approved);
    $lifecycle->assertCanTransition(TimeEntryStatus::Submitted, TimeEntryStatus::Rejected);
    expect(true)->toBeTrue();
});

it('does not allow approved entries to be changed', function (): void {
    expect(fn () => (new TimeEntryLifecycle())->assertCanTransition(TimeEntryStatus::Approved, TimeEntryStatus::Rejected))
        ->toThrow(\DomainException::class);
});

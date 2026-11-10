<?php

declare(strict_types=1);

use App\Domain\Billing\BillingPeriodLifecycle;
use App\Domain\Billing\BillingPeriodStatus;

it('allows an open period to close', function (): void {
    (new BillingPeriodLifecycle())->assertCanClose(BillingPeriodStatus::Open);
    expect(true)->toBeTrue();
});

it('rejects closing a closed period', function (): void {
    expect(fn () => (new BillingPeriodLifecycle())->assertCanClose(BillingPeriodStatus::Closed))
        ->toThrow(\DomainException::class);
});

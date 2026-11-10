<?php

declare(strict_types=1);

use App\Domain\Contracts\ContractLifecycle;
use App\Domain\Contracts\ContractStatus;

it('allows a draft contract to become active', function (): void {
    (new ContractLifecycle())->assertCanTransition(ContractStatus::Draft, ContractStatus::Active);

    expect(true)->toBeTrue();
});

it('rejects transitions from a terminated contract', function (): void {
    expect(fn () => (new ContractLifecycle())->assertCanTransition(ContractStatus::Terminated, ContractStatus::Active))
        ->toThrow(\DomainException::class);
});

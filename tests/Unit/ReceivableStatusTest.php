<?php

declare(strict_types=1);

use App\Domain\Finance\ReceivableStatus;

it('has distinct open, partial and paid states', function (): void {
    expect(ReceivableStatus::Open->value)->toBe('open')
        ->and(ReceivableStatus::Partial->value)->toBe('partial')
        ->and(ReceivableStatus::Paid->value)->toBe('paid');
});

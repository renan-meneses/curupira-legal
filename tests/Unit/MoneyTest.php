<?php

declare(strict_types=1);

use App\Domain\Billing\Money;

it('calculates hourly amounts using integer cents', function (): void {
    expect(Money::fromDecimal('100.00')->hourlyForMinutes(90)->decimal())->toBe('150.00')
        ->and(Money::fromDecimal('99.99')->hourlyForMinutes(30)->decimal())->toBe('50.00');
});

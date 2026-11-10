<?php

declare(strict_types=1);

use App\Domain\Billing\Money;
use App\Domain\Taxation\TaxEngine;

it('calculates only configured tax rules and separates treatments', function (): void {
    $result = (new TaxEngine())->calculate(Money::fromDecimal('1000.00'), [
        ['code' => 'ISS', 'basis_points' => 500, 'treatment' => 'withheld'],
        ['code' => 'PIS', 'basis_points' => 165, 'treatment' => 'outside_invoice'],
        ['code' => 'ESTIMATED', 'basis_points' => 1000],
    ]);

    expect($result['withheld_cents'])->toBe(5000)
        ->and($result['outside_invoice_cents'])->toBe(1650)
        ->and($result['estimated_cents'])->toBe(10000)
        ->and($result['lines'])->toHaveCount(3);
});

it('does not invent taxes when configuration is empty', function (): void {
    expect((new TaxEngine())->calculate(Money::fromDecimal('1000.00'), []))
        ->toMatchArray(['withheld_cents' => 0, 'outside_invoice_cents' => 0, 'estimated_cents' => 0]);
});

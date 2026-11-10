<?php

declare(strict_types=1);

namespace App\Domain\Taxation;

use App\Domain\Billing\Money;

final class TaxEngine
{
    /** @param list<array{code: string, basis_points: int, treatment?: string}> $rules */
    public function calculate(Money $base, array $rules): array
    {
        $withheld = 0;
        $outsideInvoice = 0;
        $estimated = 0;
        $lines = [];

        foreach ($rules as $rule) {
            $basisPoints = (int) ($rule['basis_points'] ?? 0);
            if ($basisPoints < 0 || $basisPoints > 10000 || !isset($rule['code'])) {
                continue;
            }

            $amountCents = intdiv(($base->cents * $basisPoints) + 5000, 10000);
            $treatment = $rule['treatment'] ?? 'estimated';
            $lines[] = ['code' => (string) $rule['code'], 'basis_points' => $basisPoints, 'amount_cents' => $amountCents, 'treatment' => $treatment];

            match ($treatment) {
                'withheld' => $withheld += $amountCents,
                'outside_invoice' => $outsideInvoice += $amountCents,
                default => $estimated += $amountCents,
            };
        }

        return ['base_cents' => $base->cents, 'withheld_cents' => $withheld, 'outside_invoice_cents' => $outsideInvoice, 'estimated_cents' => $estimated, 'lines' => $lines];
    }
}

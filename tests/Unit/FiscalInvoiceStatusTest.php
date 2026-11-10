<?php

declare(strict_types=1);

use App\Domain\Fiscal\FiscalInvoiceStatus;

it('keeps fiscal submission processing until provider reconciliation', function (): void {
    expect(FiscalInvoiceStatus::Draft->value)->toBe('draft')
        ->and(FiscalInvoiceStatus::Processing->value)->toBe('processing')
        ->and(FiscalInvoiceStatus::Authorized->value)->toBe('authorized');
});

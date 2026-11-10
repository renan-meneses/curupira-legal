<?php

declare(strict_types=1);

use App\Domain\Fiscal\FiscalInvoiceStatus;
use App\Infrastructure\Integrations\Fiscal\MockFiscalProvider;

it('keeps reconciliation pending when the mock provider is used', function (): void {
    $provider = new MockFiscalProvider();
    $submission = $provider->submit(['dps' => 'example']);
    $status = $provider->queryStatus($submission->protocol);

    expect($status->status)->toBe(FiscalInvoiceStatus::Processing)
        ->and($status->status)->not->toBe(FiscalInvoiceStatus::Authorized);
});

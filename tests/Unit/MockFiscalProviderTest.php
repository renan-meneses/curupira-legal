<?php

declare(strict_types=1);

use App\Domain\Fiscal\FiscalInvoiceStatus;
use App\Infrastructure\Integrations\Fiscal\MockFiscalProvider;

it('never reports a mock submission as authorized', function (): void {
    $provider = new MockFiscalProvider();
    $submitted = $provider->submit(['service_code' => '0101']);
    $status = $provider->queryStatus($submitted->protocol);

    expect($submitted->status)->toBe(FiscalInvoiceStatus::Processing)
        ->and($status->status)->toBe(FiscalInvoiceStatus::Processing);
});

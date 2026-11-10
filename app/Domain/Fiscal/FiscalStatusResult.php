<?php

declare(strict_types=1);

namespace App\Domain\Fiscal;

final readonly class FiscalStatusResult
{
    public function __construct(public string $protocol, public FiscalInvoiceStatus $status, public array $response = []) {}
}

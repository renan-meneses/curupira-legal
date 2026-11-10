<?php

declare(strict_types=1);

namespace App\Infrastructure\Integrations\Fiscal;

use App\Domain\Fiscal\FiscalInvoiceStatus;
use App\Domain\Fiscal\FiscalProviderInterface;
use App\Domain\Fiscal\FiscalStatusResult;
use App\Domain\Fiscal\FiscalSubmissionResult;

final class MockFiscalProvider implements FiscalProviderInterface
{
    public function submit(array $dpsPayload): FiscalSubmissionResult
    {
        $protocol = 'MOCK-'.strtoupper(substr(hash('sha256', json_encode($dpsPayload, JSON_THROW_ON_ERROR)), 0, 24));
        return new FiscalSubmissionResult($protocol, FiscalInvoiceStatus::Processing, ['provider' => 'mock', 'message' => 'Submissão simulada; aguarda reconciliação.']);
    }

    public function queryStatus(string $protocol): FiscalStatusResult
    {
        return new FiscalStatusResult($protocol, FiscalInvoiceStatus::Processing, ['provider' => 'mock', 'message' => 'O mock não confirma autorização fiscal.']);
    }

    public function retrieveXml(string $protocol): string
    {
        throw new \RuntimeException('O mock não possui XML fiscal autorizado.');
    }

    public function cancel(string $protocol, string $reason): FiscalStatusResult
    {
        return new FiscalStatusResult($protocol, FiscalInvoiceStatus::Cancelled, ['provider' => 'mock', 'reason' => $reason]);
    }
}

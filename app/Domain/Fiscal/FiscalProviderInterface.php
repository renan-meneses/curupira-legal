<?php

declare(strict_types=1);

namespace App\Domain\Fiscal;

interface FiscalProviderInterface
{
    public function submit(array $dpsPayload): FiscalSubmissionResult;
    public function queryStatus(string $protocol): FiscalStatusResult;
    public function retrieveXml(string $protocol): string;
    public function cancel(string $protocol, string $reason): FiscalStatusResult;
}

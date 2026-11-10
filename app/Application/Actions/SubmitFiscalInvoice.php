<?php

declare(strict_types=1);

namespace App\Application\Actions;

use App\Domain\Audit\AuditLogger;
use App\Domain\Fiscal\FiscalInvoiceStatus;
use App\Domain\Fiscal\FiscalProviderInterface;
use App\Domain\Organizations\OrganizationContext;
use App\Models\FiscalInvoice;
use Illuminate\Support\Facades\DB;

final class SubmitFiscalInvoice
{
    public function __construct(private readonly FiscalProviderInterface $provider, private readonly AuditLogger $audit) {}

    public function execute(FiscalInvoice $invoice, OrganizationContext $context, ?string $userId = null): FiscalInvoice
    {
        return DB::transaction(function () use ($invoice, $context): FiscalInvoice {
            $locked = FiscalInvoice::query()->where('organization_id', $context->id())->whereKey($invoice->getKey())->lockForUpdate()->firstOrFail();
            abort_unless($locked->status === FiscalInvoiceStatus::Draft, 422, 'A nota fiscal não está em rascunho.');
            $result = $this->provider->submit($locked->dps_payload ?? []);
            $locked->protocol = $result->protocol;
            $locked->status = $result->status;
            $locked->provider_response = $result->response;
            $locked->save();
            $this->audit->record($context->id(), $userId, 'fiscal.invoice_submitted', FiscalInvoice::class, $locked->getKey(), ['protocol' => $result->protocol, 'status' => $result->status->value]);
            return $locked->fresh();
        });
    }
}

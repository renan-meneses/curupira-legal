<?php

declare(strict_types=1);

namespace App\Application\Actions;

use App\Domain\Audit\AuditLogger;
use App\Domain\Fiscal\FiscalInvoiceStatus;
use App\Domain\Fiscal\FiscalProviderInterface;
use App\Domain\Organizations\OrganizationContext;
use App\Models\FiscalInvoice;
use Illuminate\Support\Facades\DB;

final class ReconcileFiscalInvoice
{
    public function __construct(private readonly FiscalProviderInterface $provider, private readonly AuditLogger $audit) {}

    public function execute(FiscalInvoice $invoice, OrganizationContext $context, ?string $userId = null): FiscalInvoice
    {
        return DB::transaction(function () use ($invoice, $context, $userId): FiscalInvoice {
            $locked = FiscalInvoice::query()->where('organization_id', $context->id())->whereKey($invoice->getKey())->lockForUpdate()->firstOrFail();
            abort_unless($locked->protocol !== null, 422, 'A invoice ainda não possui protocolo fiscal.');
            $result = $this->provider->queryStatus($locked->protocol);
            $locked->provider_response = $result->response;
            if ($locked->status !== $result->status) {
                $locked->status = $result->status;
                if ($result->status === FiscalInvoiceStatus::Authorized) {
                    $locked->authorized_at = now();
                }
                $locked->save();
            }
            $locked->events()->create(['event_type' => 'status_reconciled', 'status' => $result->status->value, 'payload' => $result->response, 'occurred_at' => now()]);
            $this->audit->record($context->id(), $userId, 'fiscal.invoice_reconciled', FiscalInvoice::class, $locked->getKey(), ['protocol' => $locked->protocol, 'status' => $result->status->value]);
            return $locked->fresh(['events']);
        });
    }
}

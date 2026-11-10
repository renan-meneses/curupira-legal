<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Application\Actions\SubmitFiscalInvoice;
use App\Application\Actions\ReconcileFiscalInvoice;
use App\Domain\Fiscal\FiscalInvoiceStatus;
use App\Domain\Organizations\OrganizationContext;
use App\Http\Controllers\Controller;
use App\Http\Requests\PrepareFiscalInvoiceRequest;
use App\Models\BillingRecord;
use App\Models\FiscalInvoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class FiscalController extends Controller
{
    public function prepare(PrepareFiscalInvoiceRequest $request, OrganizationContext $context): JsonResponse
    {
        $data = $request->validated();
        $billing = BillingRecord::query()->where('organization_id', $context->id())->whereKey($data['billing_record_id'])->firstOrFail();
        abort_unless($billing->status === 'finalized', 422, 'O billing precisa estar finalizado antes da preparação fiscal.');
        $invoice = FiscalInvoice::query()->create(['organization_id' => $context->id(), 'billing_record_id' => $billing->getKey(), 'tax_snapshot_id' => $data['tax_snapshot_id'] ?? null, 'provider' => 'mock', 'status' => FiscalInvoiceStatus::Draft, 'dps_payload' => $data['dps_payload']]);
        return response()->json(['data' => $invoice], 201);
    }

    public function submit(Request $request, FiscalInvoice $fiscalInvoice, OrganizationContext $context, SubmitFiscalInvoice $submit): JsonResponse
    {
        return response()->json(['data' => $submit->execute($fiscalInvoice, $context, (string) $request->user()->getAuthIdentifier())]);
    }

    public function reconcile(Request $request, FiscalInvoice $fiscalInvoice, OrganizationContext $context, ReconcileFiscalInvoice $reconcile): JsonResponse
    {
        return response()->json(['data' => $reconcile->execute($fiscalInvoice, $context, (string) $request->user()->getAuthIdentifier())]);
    }
}

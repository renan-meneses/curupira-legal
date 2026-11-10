<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Application\Actions\CreateReceivableFromBilling;
use App\Application\Actions\RegisterPayment;
use App\Domain\Organizations\OrganizationContext;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateReceivableRequest;
use App\Http\Requests\RegisterPaymentRequest;
use App\Models\BillingRecord;
use App\Models\Receivable;
use Illuminate\Http\JsonResponse;

final class FinanceController extends Controller
{
    public function receivables(OrganizationContext $context): JsonResponse
    {
        return response()->json(['data' => Receivable::query()->with('customer:id,name,document')->where('organization_id', $context->id())->latest('due_on')->paginate(25)]);
    }

    public function createReceivable(CreateReceivableRequest $request, OrganizationContext $context, CreateReceivableFromBilling $create): JsonResponse
    {
        $record = BillingRecord::query()->where('organization_id', $context->id())->whereKey($request->validated('billing_record_id'))->firstOrFail();
        $receivable = $create->execute($record, $request->validated('due_on'), $context);
        return response()->json(['data' => $receivable->load('customer:id,name,document')], 201);
    }

    public function registerPayment(RegisterPaymentRequest $request, Receivable $receivable, OrganizationContext $context, RegisterPayment $register): JsonResponse
    {
        $payment = $register->execute($receivable, $request->validated(), $context, (string) $request->user()->getAuthIdentifier());
        return response()->json(['data' => $payment->load('receivable')], 201);
    }
}

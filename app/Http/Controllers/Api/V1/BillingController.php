<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Application\Actions\FinalizeBillingPeriod;
use App\Application\Actions\GenerateHourlyBilling;
use App\Domain\Organizations\OrganizationContext;
use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateBillingRequest;
use App\Models\BillingPeriod;
use App\Models\Contract;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;

final class BillingController extends Controller
{
    public function generate(GenerateBillingRequest $request, OrganizationContext $context, GenerateHourlyBilling $generate): JsonResponse
    {
        $data = $request->validated();
        $contract = Contract::query()->where('organization_id', $context->id())->whereKey($data['contract_id'])->firstOrFail();
        $record = $generate->execute($contract, CarbonImmutable::parse($data['starts_on']), CarbonImmutable::parse($data['ends_on']), $context);

        return response()->json(['data' => $record->load(['contract:id,title', 'period'])], 201);
    }

    public function finalize(BillingPeriod $billingPeriod, OrganizationContext $context, FinalizeBillingPeriod $finalize): JsonResponse
    {
        return response()->json(['data' => $finalize->execute($billingPeriod, $context)]);
    }
}

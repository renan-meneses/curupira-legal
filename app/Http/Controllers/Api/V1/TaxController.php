<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Application\Actions\CreateTaxPreview;
use App\Application\Actions\ReviewTaxConfiguration;
use App\Domain\Organizations\OrganizationContext;
use App\Http\Controllers\Controller;
use App\Http\Requests\PreviewTaxRequest;
use App\Http\Requests\ReviewTaxConfigurationRequest;
use App\Http\Requests\StoreTaxConfigurationRequest;
use App\Models\TaxConfiguration;
use Illuminate\Http\JsonResponse;

final class TaxController extends Controller
{
    public function index(OrganizationContext $context): JsonResponse { return response()->json(['data' => TaxConfiguration::query()->where('organization_id', $context->id())->latest('effective_from')->paginate(25)]); }

    public function store(StoreTaxConfigurationRequest $request, OrganizationContext $context): JsonResponse
    {
        $configuration = TaxConfiguration::query()->create([...$request->validated(), 'organization_id' => $context->id(), 'status' => 'draft']);
        return response()->json(['data' => $configuration], 201);
    }

    public function review(ReviewTaxConfigurationRequest $request, TaxConfiguration $taxConfiguration, OrganizationContext $context, ReviewTaxConfiguration $review): JsonResponse
    {
        return response()->json(['data' => $review->execute($taxConfiguration, $request->validated('status'), $context, (string) $request->user()->getAuthIdentifier())]);
    }

    public function preview(PreviewTaxRequest $request, OrganizationContext $context, CreateTaxPreview $preview): JsonResponse
    {
        return response()->json(['data' => $preview->execute($request->validated(), $context)]);
    }
}

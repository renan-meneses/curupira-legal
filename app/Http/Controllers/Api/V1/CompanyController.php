<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Customers\DocumentNumber;
use App\Domain\Organizations\OrganizationContext;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Models\Company;
use Illuminate\Http\JsonResponse;

final class CompanyController extends Controller
{
    public function index(OrganizationContext $context): JsonResponse
    {
        return response()->json(['data' => Company::query()
            ->where('organization_id', $context->id())
            ->latest('created_at')
            ->paginate(25)]);
    }

    public function store(StoreCompanyRequest $request, OrganizationContext $context): JsonResponse
    {
        $data = $request->validated();
        $document = DocumentNumber::cpfOrCnpj($data['document']);
        $data['document'] = $document->value;
        $data['organization_id'] = $context->id();

        $company = Company::query()->create($data);

        return response()->json(['data' => $company], 201);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Customers\DocumentNumber;
use App\Domain\Organizations\OrganizationContext;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;

final class CustomerController extends Controller
{
    public function index(OrganizationContext $context): JsonResponse
    {
        return response()->json(['data' => Customer::query()
            ->where('organization_id', $context->id())
            ->latest('created_at')
            ->paginate(25)]);
    }

    public function store(StoreCustomerRequest $request, OrganizationContext $context): JsonResponse
    {
        $data = $request->validated();
        $data['document'] = DocumentNumber::cpfOrCnpj($data['document'])->value;
        $data['organization_id'] = $context->id();

        $customer = Customer::query()->create($data);

        return response()->json(['data' => $customer], 201);
    }
}

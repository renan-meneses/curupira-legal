<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Organizations\OrganizationContext;
use App\Domain\Contracts\ContractStatus;
use App\Application\Actions\TransitionContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContractRequest;
use App\Http\Requests\TransitionContractRequest;
use App\Models\Contract;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;

final class ContractController extends Controller
{
    public function index(OrganizationContext $context): JsonResponse
    {
        return response()->json(['data' => Contract::query()
            ->with('customer:id,name,document')
            ->where('organization_id', $context->id())
            ->latest('created_at')
            ->paginate(25)]);
    }

    public function store(StoreContractRequest $request, OrganizationContext $context): JsonResponse
    {
        $data = $request->validated();
        $customerExists = Customer::query()
            ->where('organization_id', $context->id())
            ->whereKey($data['customer_id'])
            ->exists();

        abort_unless($customerExists, 404, 'Cliente não encontrado nesta organização.');

        $data['organization_id'] = $context->id();
        $contract = Contract::query()->create($data);

        return response()->json(['data' => $contract->load('customer:id,name,document')], 201);
    }

    public function transition(TransitionContractRequest $request, Contract $contract, OrganizationContext $context, TransitionContract $transition): JsonResponse
    {
        $updated = $transition->execute(
            $contract,
            ContractStatus::from($request->validated('status')),
            $request->validated('reason'),
            $context,
            $request->user()?->getAuthIdentifier(),
        );

        return response()->json(['data' => $updated]);
    }
}

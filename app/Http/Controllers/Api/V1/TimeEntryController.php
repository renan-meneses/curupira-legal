<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Application\Actions\ReviewTimeEntry;
use App\Domain\Organizations\OrganizationContext;
use App\Domain\TimeTracking\TimeEntryStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewTimeEntryRequest;
use App\Http\Requests\StoreTimeEntryRequest;
use App\Models\Contract;
use App\Models\TimeEntry;
use Illuminate\Http\JsonResponse;

final class TimeEntryController extends Controller
{
    public function index(OrganizationContext $context): JsonResponse
    {
        return response()->json(['data' => TimeEntry::query()->with(['contract:id,title', 'user:id,name'])->where('organization_id', $context->id())->latest('worked_on')->paginate(50)]);
    }

    public function store(StoreTimeEntryRequest $request, OrganizationContext $context): JsonResponse
    {
        $data = $request->validated();
        abort_unless(Contract::query()->where('organization_id', $context->id())->whereKey($data['contract_id'])->exists(), 404, 'Contrato não encontrado nesta organização.');
        $data['organization_id'] = $context->id();
        $data['user_id'] = $request->user()->getAuthIdentifier();
        $entry = TimeEntry::query()->create($data);

        return response()->json(['data' => $entry->load(['contract:id,title', 'user:id,name'])], 201);
    }

    public function review(ReviewTimeEntryRequest $request, TimeEntry $timeEntry, OrganizationContext $context, ReviewTimeEntry $review): JsonResponse
    {
        $entry = $review->execute($timeEntry, TimeEntryStatus::from($request->validated('status')), $request->validated('rejection_reason'), $context, (string) $request->user()->getAuthIdentifier());
        return response()->json(['data' => $entry]);
    }
}

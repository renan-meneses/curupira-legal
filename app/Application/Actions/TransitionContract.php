<?php

declare(strict_types=1);

namespace App\Application\Actions;

use App\Domain\Contracts\ContractLifecycle;
use App\Domain\Contracts\ContractStatus;
use App\Domain\Organizations\OrganizationContext;
use App\Models\Contract;
use Illuminate\Support\Facades\DB;

final class TransitionContract
{
    public function __construct(private readonly ContractLifecycle $lifecycle) {}

    public function execute(Contract $contract, ContractStatus $target, ?string $reason, OrganizationContext $context, ?string $userId): Contract
    {
        return DB::transaction(function () use ($contract, $target, $reason, $context, $userId): Contract {
            $locked = Contract::query()
                ->where('organization_id', $context->id())
                ->whereKey($contract->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $current = $locked->status instanceof ContractStatus
                ? $locked->status
                : ContractStatus::from((string) $locked->status);
            $this->lifecycle->assertCanTransition($current, $target);
            $locked->status = $target;
            $locked->save();

            $version = ((int) $locked->versions()->max('version')) + 1;
            $locked->versions()->create([
                'created_by' => $userId,
                'version' => $version,
                'change_type' => 'status_transition',
                'snapshot' => $locked->fresh()->toArray(),
                'change_reason' => $reason,
            ]);

            return $locked->fresh('customer');
        });
    }
}

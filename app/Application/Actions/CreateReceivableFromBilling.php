<?php

declare(strict_types=1);

namespace App\Application\Actions;

use App\Domain\Organizations\OrganizationContext;
use App\Models\BillingRecord;
use App\Models\Receivable;
use Illuminate\Support\Facades\DB;

final class CreateReceivableFromBilling
{
    public function execute(BillingRecord $record, string $dueOn, OrganizationContext $context): Receivable
    {
        return DB::transaction(function () use ($record, $dueOn, $context): Receivable {
            $locked = BillingRecord::query()->with('contract')->where('organization_id', $context->id())->whereKey($record->getKey())->lockForUpdate()->firstOrFail();
            abort_unless($locked->status === 'finalized', 422, 'O billing precisa estar finalizado antes de gerar um recebível.');
            $customerId = $locked->contract->customer_id;
            return Receivable::query()->firstOrCreate(
                ['billing_record_id' => $locked->getKey()],
                ['organization_id' => $context->id(), 'customer_id' => $customerId, 'due_on' => $dueOn, 'amount_cents' => $locked->gross_cents, 'paid_cents' => 0, 'status' => 'open'],
            );
        });
    }
}

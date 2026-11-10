<?php

declare(strict_types=1);

namespace App\Application\Actions;

use App\Domain\Audit\AuditLogger;
use App\Domain\Finance\ReceivableStatus;
use App\Domain\Organizations\OrganizationContext;
use App\Models\Payment;
use App\Models\Receivable;
use Illuminate\Support\Facades\DB;

final class RegisterPayment
{
    public function __construct(private readonly AuditLogger $audit) {}

    public function execute(Receivable $receivable, array $data, OrganizationContext $context, string $userId): Payment
    {
        return DB::transaction(function () use ($receivable, $data, $context, $userId): Payment {
            $locked = Receivable::query()->where('organization_id', $context->id())->whereKey($receivable->getKey())->lockForUpdate()->firstOrFail();
            $amount = (int) $data['amount_cents'];
            $outstanding = (int) $locked->amount_cents - (int) $locked->paid_cents;
            abort_if($amount > $outstanding, 422, 'O pagamento excede o saldo do recebível.');
            $payment = $locked->payments()->create(['organization_id' => $context->id(), 'registered_by' => $userId, ...$data]);
            $locked->paid_cents += $amount;
            $locked->status = $locked->paid_cents === $locked->amount_cents ? ReceivableStatus::Paid : ReceivableStatus::Partial;
            $locked->save();
            $this->audit->record($context->id(), $userId, 'finance.payment_registered', Payment::class, $payment->getKey(), ['amount_cents' => $amount, 'receivable_id' => $locked->getKey()]);
            return $payment;
        });
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Organizations\OrganizationContext;
use App\Http\Controllers\Controller;
use App\Models\BillingRecord;
use App\Models\Contract;
use App\Models\FiscalInvoice;
use App\Models\Receivable;
use App\Models\TaxCalculationSnapshot;
use App\Models\TimeEntry;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class DashboardController extends Controller
{
    public function __invoke(Request $request, OrganizationContext $context): JsonResponse
    {
        $month = (string) $request->query('month', now()->format('Y-m'));
        abort_unless(preg_match('/^\d{4}-\d{2}$/', $month) === 1, 422, 'Competência inválida.');
        $start = CarbonImmutable::createFromFormat('!Y-m', $month)->startOfMonth();
        $end = $start->endOfMonth();
        $organizationId = $context->id();

        $contracts = Contract::query()->where('organization_id', $organizationId);
        $approvedMinutes = TimeEntry::query()->where('organization_id', $organizationId)->where('status', 'approved')->whereBetween('worked_on', [$start->toDateString(), $end->toDateString()])->sum('duration_minutes');
        $billing = BillingRecord::query()->where('organization_id', $organizationId)->where('status', 'finalized')->whereHas('period', fn ($query) => $query->whereBetween('starts_on', [$start->toDateString(), $end->toDateString()]))->get(['gross_cents']);
        $receivables = Receivable::query()->where('organization_id', $organizationId)->get(['amount_cents', 'paid_cents', 'status']);
        $invoices = FiscalInvoice::query()->where('organization_id', $organizationId)->whereBetween('created_at', [$start, $end])->get(['status']);
        $taxes = TaxCalculationSnapshot::query()->where('organization_id', $organizationId)->whereBetween('competency_date', [$start->toDateString(), $end->toDateString()])->get(['result_snapshot']);

        return response()->json(['data' => [
            'period' => ['starts_on' => $start->toDateString(), 'ends_on' => $end->toDateString()],
            'metrics' => [
                'active_contracts' => (clone $contracts)->where('status', 'active')->count(),
                'expiring_contracts' => (clone $contracts)->where('status', 'active')->whereBetween('ends_on', [now()->toDateString(), now()->addDays(30)->toDateString()])->count(),
                'approved_minutes' => (int) $approvedMinutes,
                'gross_revenue_cents' => (int) $billing->sum('gross_cents'),
                'receivables_outstanding_cents' => (int) $receivables->sum(fn ($item) => (int) $item->amount_cents - (int) $item->paid_cents),
                'issued_invoices' => $invoices->where('status', 'authorized')->count(),
                'pending_invoices' => $invoices->where('status', 'processing')->count(),
                'overdue_receivables' => $receivables->where('status', 'overdue')->count(),
                'withheld_tax_cents' => (int) $taxes->sum(fn ($item) => (int) data_get($item->result_snapshot, 'withheld_cents', 0)),
            ],
        ]]);
    }
}

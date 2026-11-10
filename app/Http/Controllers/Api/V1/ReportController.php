<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Organizations\OrganizationContext;
use App\Http\Controllers\Controller;
use App\Models\Receivable;
use App\Models\Contract;
use App\Models\TimeEntry;
use App\Models\BillingRecord;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ReportController extends Controller
{
    public function receivables(Request $request, OrganizationContext $context): StreamedResponse
    {
        $month = (string) $request->query('month', now()->format('Y-m'));
        abort_unless(preg_match('/^\d{4}-\d{2}$/', $month) === 1, 422, 'Competência inválida.');
        $receivables = Receivable::query()->with('customer:id,name,document')->where('organization_id', $context->id())->whereBetween('due_on', ["{$month}-01", date('Y-m-t', strtotime("{$month}-01"))])->orderBy('due_on')->get();

        return $this->csv("recebiveis-{$month}.csv", ['Cliente', 'Documento', 'Vencimento', 'Valor (centavos)', 'Pago (centavos)', 'Saldo (centavos)', 'Status'], $receivables->map(fn ($item) => [$item->customer?->name, $item->customer?->document, $item->due_on?->format('d/m/Y'), $item->amount_cents, $item->paid_cents, $item->amount_cents - $item->paid_cents, $item->status->value]));
    }

    public function contracts(OrganizationContext $context): StreamedResponse
    {
        $contracts = Contract::query()->with('customer:id,name')->where('organization_id', $context->id())->orderBy('title')->get();
        return $this->csv('contratos.csv', ['Título', 'Cliente', 'Tipo', 'Cobrança', 'Status', 'Início', 'Fim'], $contracts->map(fn ($item) => [$item->title, $item->customer?->name, $item->contract_type, $item->billing_model->value, $item->status->value, $item->starts_on?->format('d/m/Y'), $item->ends_on?->format('d/m/Y')]));
    }

    public function timeEntries(Request $request, OrganizationContext $context): StreamedResponse
    {
        $month = (string) $request->query('month', now()->format('Y-m'));
        abort_unless(preg_match('/^\d{4}-\d{2}$/', $month) === 1, 422, 'Competência inválida.');
        $entries = TimeEntry::query()->with(['contract:id,title', 'user:id,name'])->where('organization_id', $context->id())->whereBetween('worked_on', ["{$month}-01", date('Y-m-t', strtotime("{$month}-01"))])->orderBy('worked_on')->get();
        return $this->csv("horas-{$month}.csv", ['Data', 'Contrato', 'Usuário', 'Minutos', 'Faturável', 'Status'], $entries->map(fn ($item) => [$item->worked_on?->format('d/m/Y'), $item->contract?->title, $item->user?->name, $item->duration_minutes, $item->billable ? 'Sim' : 'Não', $item->status->value]));
    }

    public function billing(Request $request, OrganizationContext $context): StreamedResponse
    {
        $month = (string) $request->query('month', now()->format('Y-m'));
        abort_unless(preg_match('/^\d{4}-\d{2}$/', $month) === 1, 422, 'Competência inválida.');
        $records = BillingRecord::query()->with('contract:id,title')->where('organization_id', $context->id())->whereBetween('created_at', ["{$month}-01", date('Y-m-t 23:59:59', strtotime("{$month}-01"))])->orderBy('created_at')->get();
        return $this->csv("billing-{$month}.csv", ['Contrato', 'Modelo', 'Bruto (centavos)', 'Status', 'Idempotency key'], $records->map(fn ($item) => [$item->contract?->title, $item->billing_model, $item->gross_cents, $item->status, $item->idempotency_key]));
    }

    private function csv(string $filename, array $header, iterable $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($header, $rows): void {
            $output = fopen('php://output', 'wb');
            fputcsv($output, $header, ';');
            foreach ($rows as $row) fputcsv($output, $row, ';');
            fclose($output);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}

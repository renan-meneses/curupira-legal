<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Application\Actions\ReconcileFiscalInvoice;
use App\Domain\Fiscal\FiscalInvoiceStatus;
use App\Domain\Organizations\OrganizationContext;
use App\Models\FiscalInvoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class ReconcilePendingFiscalInvoices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;

    public function handle(ReconcileFiscalInvoice $reconcile, OrganizationContext $context): void
    {
        FiscalInvoice::query()->where('status', FiscalInvoiceStatus::Processing)->chunkById(100, function ($invoices) use ($reconcile, $context): void {
            foreach ($invoices as $invoice) {
                $context->set((string) $invoice->organization_id);
                try {
                    $reconcile->execute($invoice, $context);
                } finally {
                    $context->clear();
                }
            }
        });
    }
}

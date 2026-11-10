<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\FiscalInvoice;
use App\Notifications\FiscalRejectionNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class NotifyRejectedFiscalInvoices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        FiscalInvoice::query()->with('organization.users')->where('status', 'rejected')->chunkById(100, function ($invoices): void {
            foreach ($invoices as $invoice) {
                foreach ($invoice->organization->users as $user) {
                    $key = "fiscal-rejected:{$invoice->getKey()}";
                    if (!$user->notifications()->where('type', FiscalRejectionNotification::class)->whereJsonContains('data->notification_key', $key)->exists()) {
                        $user->notify(new FiscalRejectionNotification($invoice));
                    }
                }
            }
        });
    }
}

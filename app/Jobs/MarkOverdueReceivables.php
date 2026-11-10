<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Domain\Finance\ReceivableStatus;
use App\Models\Receivable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class MarkOverdueReceivables implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function handle(): void
    {
        Receivable::query()
            ->whereIn('status', [ReceivableStatus::Open, ReceivableStatus::Partial])
            ->whereDate('due_on', '<', now()->toDateString())
            ->update(['status' => ReceivableStatus::Overdue]);
    }
}

<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Contract;
use App\Notifications\ContractExpiringNotification;
use Carbon\CarbonImmutable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class NotifyExpiringContracts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $today = CarbonImmutable::today();
        Contract::query()->with('organization.users')->where('status', 'active')->whereBetween('ends_on', [$today->toDateString(), $today->addDays(30)->toDateString()])->chunkById(100, function ($contracts) use ($today): void {
            foreach ($contracts as $contract) {
                $days = $today->diffInDays($contract->ends_on);
                foreach ($contract->organization->users as $user) {
                    $key = "contract-expiring:{$contract->getKey()}:{$contract->ends_on?->toDateString()}";
                    if (!$user->notifications()->where('type', ContractExpiringNotification::class)->whereJsonContains('data->notification_key', $key)->exists()) {
                        $user->notify(new ContractExpiringNotification($contract, $days));
                    }
                }
            }
        });
    }
}

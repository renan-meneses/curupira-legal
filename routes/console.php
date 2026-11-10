<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\MarkOverdueReceivables;
use App\Jobs\ReconcilePendingFiscalInvoices;
use App\Jobs\NotifyExpiringContracts;
use App\Jobs\NotifyRejectedFiscalInvoices;

Artisan::command('inspire', function (): void {
    $this->comment(Inspiring::quote());
})->purpose('Exibir uma frase inspiradora');

Schedule::job(new ReconcilePendingFiscalInvoices())->everyFiveMinutes()->withoutOverlapping();
Schedule::job(new MarkOverdueReceivables())->dailyAt('00:15')->withoutOverlapping();
Schedule::job(new NotifyExpiringContracts())->dailyAt('08:00')->withoutOverlapping();
Schedule::job(new NotifyRejectedFiscalInvoices())->everyTenMinutes()->withoutOverlapping();

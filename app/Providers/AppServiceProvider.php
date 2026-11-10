<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Fiscal\FiscalProviderInterface;
use App\Infrastructure\Integrations\Fiscal\MockFiscalProvider;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(FiscalProviderInterface::class, MockFiscalProvider::class);
    }
    public function boot(): void {}
}

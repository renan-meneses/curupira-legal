<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Organizations\OrganizationContext;
use Illuminate\Support\ServiceProvider;

final class OrganizationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(OrganizationContext::class, fn (): OrganizationContext => new OrganizationContext());
    }
}

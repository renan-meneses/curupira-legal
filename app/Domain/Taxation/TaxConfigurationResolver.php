<?php

declare(strict_types=1);

namespace App\Domain\Taxation;

use App\Models\TaxConfiguration;
use Carbon\CarbonImmutable;

final class TaxConfigurationResolver
{
    public function resolve(string $organizationId, string $serviceCode, CarbonImmutable $competencyDate, ?string $issuerMunicipality, ?string $customerMunicipality, ?string $taxRegime): TaxConfiguration
    {
        return TaxConfiguration::query()
            ->where('organization_id', $organizationId)
            ->where('service_code', $serviceCode)
            ->where('status', 'active')
            ->whereDate('effective_from', '<=', $competencyDate->toDateString())
            ->where(function ($query) use ($competencyDate): void {
                $query->whereNull('effective_to')->orWhereDate('effective_to', '>=', $competencyDate->toDateString());
            })
            ->when($issuerMunicipality !== null, fn ($query) => $query->where(function ($query) use ($issuerMunicipality): void { $query->whereNull('issuer_municipality_code')->orWhere('issuer_municipality_code', $issuerMunicipality); }))
            ->when($customerMunicipality !== null, fn ($query) => $query->where(function ($query) use ($customerMunicipality): void { $query->whereNull('customer_municipality_code')->orWhere('customer_municipality_code', $customerMunicipality); }))
            ->when($taxRegime !== null, fn ($query) => $query->where(function ($query) use ($taxRegime): void { $query->whereNull('tax_regime')->orWhere('tax_regime', $taxRegime); }))
            ->orderByDesc('effective_from')
            ->firstOrFail();
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Actions;

use App\Domain\Billing\Money;
use App\Domain\Organizations\OrganizationContext;
use App\Domain\Taxation\TaxConfigurationResolver;
use App\Domain\Taxation\TaxEngine;
use App\Models\TaxCalculationSnapshot;
use Carbon\CarbonImmutable;

final class CreateTaxPreview
{
    public function __construct(private readonly TaxConfigurationResolver $resolver, private readonly TaxEngine $engine) {}

    public function execute(array $data, OrganizationContext $context): TaxCalculationSnapshot
    {
        $date = CarbonImmutable::parse($data['competency_date']);
        $configuration = $this->resolver->resolve($context->id(), $data['service_code'], $date, $data['issuer_municipality_code'] ?? null, $data['customer_municipality_code'] ?? null, $data['tax_regime'] ?? null);
        $input = ['service_code' => $data['service_code'], 'competency_date' => $date->toDateString(), 'base_cents' => (int) $data['base_cents'], 'issuer_municipality_code' => $data['issuer_municipality_code'] ?? null, 'customer_municipality_code' => $data['customer_municipality_code'] ?? null, 'tax_regime' => $data['tax_regime'] ?? null];
        $result = $this->engine->calculate(Money::fromCents((int) $data['base_cents']), $configuration->rules);

        return TaxCalculationSnapshot::query()->create(['organization_id' => $context->id(), 'tax_configuration_id' => $configuration->getKey(), 'source_type' => $data['source_type'] ?? null, 'source_id' => $data['source_id'] ?? null, 'competency_date' => $date, 'input_snapshot' => $input, 'result_snapshot' => $result])->load('configuration');
    }
}

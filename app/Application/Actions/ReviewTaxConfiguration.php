<?php

declare(strict_types=1);

namespace App\Application\Actions;

use App\Domain\Audit\AuditLogger;
use App\Domain\Organizations\OrganizationRole;
use App\Domain\Organizations\OrganizationContext;
use App\Models\OrganizationMembership;
use App\Models\TaxConfiguration;
use Illuminate\Support\Facades\DB;

final class ReviewTaxConfiguration
{
    public function __construct(private readonly AuditLogger $audit) {}

    public function execute(TaxConfiguration $configuration, string $status, OrganizationContext $context, string $userId): TaxConfiguration
    {
        return DB::transaction(function () use ($configuration, $status, $context, $userId): TaxConfiguration {
            $membership = OrganizationMembership::query()->where('organization_id', $context->id())->where('user_id', $userId)->firstOrFail();
            $role = $membership->role instanceof OrganizationRole ? $membership->role : OrganizationRole::from((string) $membership->role);
            abort_unless(in_array($role, [OrganizationRole::Administrator, OrganizationRole::Accountant], true), 403, 'Apenas administradores ou contadores podem revisar configuração fiscal.');
            $locked = TaxConfiguration::query()->where('organization_id', $context->id())->whereKey($configuration->getKey())->lockForUpdate()->firstOrFail();
            $locked->status = $status;
            $locked->reviewed_by = $userId;
            $locked->reviewed_at = now();
            $locked->save();
            $this->audit->record($context->id(), $userId, 'tax.configuration_reviewed', TaxConfiguration::class, $locked->getKey(), ['status' => $status]);
            return $locked->fresh();
        });
    }
}

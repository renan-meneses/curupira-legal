<?php

declare(strict_types=1);

use App\Domain\Organizations\OrganizationRole;

it('restricts member management to organization administrators', function (): void {
    expect(OrganizationRole::Administrator->canManageMembers())->toBeTrue()
        ->and(OrganizationRole::FinancialManager->canManageMembers())->toBeFalse()
        ->and(OrganizationRole::ReadOnly->canManageMembers())->toBeFalse();
});

it('allows financial writes only to administrators and financial managers', function (): void {
    expect(OrganizationRole::Administrator->canWriteFinancialData())->toBeTrue()
        ->and(OrganizationRole::FinancialManager->canWriteFinancialData())->toBeTrue()
        ->and(OrganizationRole::Accountant->canWriteFinancialData())->toBeFalse()
        ->and(OrganizationRole::ReadOnly->canWriteFinancialData())->toBeFalse();
});

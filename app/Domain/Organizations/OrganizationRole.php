<?php

declare(strict_types=1);

namespace App\Domain\Organizations;

enum OrganizationRole: string
{
    case Administrator = 'organization_administrator';
    case FinancialManager = 'financial_manager';
    case Accountant = 'accountant';
    case Employee = 'employee';
    case ReadOnly = 'read_only';

    public function canManageMembers(): bool
    {
        return $this === self::Administrator;
    }

    public function canWriteFinancialData(): bool
    {
        return in_array($this, [self::Administrator, self::FinancialManager], true);
    }

    public function canReadFinancialData(): bool
    {
        return $this !== self::Employee;
    }
}

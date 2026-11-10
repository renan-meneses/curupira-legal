<?php

declare(strict_types=1);

namespace App\Policies;

use App\Domain\Organizations\OrganizationRole;
use App\Models\Organization;
use App\Models\User;

final class OrganizationPolicy
{
    public function view(User $user, Organization $organization): bool
    {
        return $user->organizations()->whereKey($organization->getKey())->exists();
    }

    public function update(User $user, Organization $organization): bool
    {
        return $this->role($user, $organization) === OrganizationRole::Administrator;
    }

    public function manageMembers(User $user, Organization $organization): bool
    {
        return $this->role($user, $organization)?->canManageMembers() ?? false;
    }

    private function role(User $user, Organization $organization): ?OrganizationRole
    {
        $membership = $user->organizations()
            ->whereKey($organization->getKey())
            ->first()?->pivot;

        return $membership?->role instanceof OrganizationRole
            ? $membership->role
            : ($membership?->role ? OrganizationRole::tryFrom((string) $membership->role) : null);
    }
}

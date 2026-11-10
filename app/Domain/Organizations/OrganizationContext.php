<?php

declare(strict_types=1);

namespace App\Domain\Organizations;

use RuntimeException;

final class OrganizationContext
{
    private ?string $organizationId = null;

    public function set(string $organizationId): void
    {
        if ($organizationId === '') {
            throw new RuntimeException('O contexto da organização não pode ser vazio.');
        }

        $this->organizationId = $organizationId;
    }

    public function id(): string
    {
        return $this->organizationId ?? throw new RuntimeException('Nenhuma organização foi selecionada.');
    }

    public function hasOrganization(): bool
    {
        return $this->organizationId !== null;
    }

    public function clear(): void
    {
        $this->organizationId = null;
    }
}

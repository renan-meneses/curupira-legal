<?php

declare(strict_types=1);

namespace App\Domain\Contracts;

use DomainException;

final class ContractLifecycle
{
    /** @return list<ContractStatus> */
    public function allowedTransitions(ContractStatus $from): array
    {
        return match ($from) {
            ContractStatus::Draft => [ContractStatus::Active, ContractStatus::Terminated],
            ContractStatus::Active => [ContractStatus::Suspended, ContractStatus::Expired, ContractStatus::Terminated, ContractStatus::Renewed],
            ContractStatus::Suspended => [ContractStatus::Active, ContractStatus::Terminated],
            ContractStatus::Expired => [ContractStatus::Renewed, ContractStatus::Terminated],
            ContractStatus::Terminated, ContractStatus::Renewed => [],
        };
    }

    public function assertCanTransition(ContractStatus $from, ContractStatus $to): void
    {
        if (!in_array($to, $this->allowedTransitions($from), true)) {
            throw new DomainException("Não é possível alterar contrato de {$from->value} para {$to->value}.");
        }
    }
}

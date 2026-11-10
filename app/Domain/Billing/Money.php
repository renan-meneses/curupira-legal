<?php

declare(strict_types=1);

namespace App\Domain\Billing;

use InvalidArgumentException;

final readonly class Money
{
    private function __construct(public int $cents) {}

    public static function fromCents(int $cents): self
    {
        if ($cents < 0) {
            throw new InvalidArgumentException('Centavos não podem ser negativos.');
        }

        return new self($cents);
    }

    public static function fromDecimal(string|int $amount): self
    {
        $value = trim((string) $amount);
        if (preg_match('/^(\d+)(?:\.(\d{1,2}))?$/', $value, $matches) !== 1) {
            throw new InvalidArgumentException('Valor monetário inválido.');
        }

        $fraction = str_pad($matches[2] ?? '', 2, '0');
        return new self(((int) $matches[1] * 100) + (int) $fraction);
    }

    public function add(self $other): self
    {
        return new self($this->cents + $other->cents);
    }

    public function hourlyForMinutes(int $minutes): self
    {
        if ($minutes < 0) {
            throw new InvalidArgumentException('Minutos não podem ser negativos.');
        }

        return new self(intdiv(($this->cents * $minutes) + 30, 60));
    }

    public function decimal(): string
    {
        return sprintf('%d.%02d', intdiv($this->cents, 100), $this->cents % 100);
    }
}

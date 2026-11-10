<?php

declare(strict_types=1);

namespace App\Domain\Customers;

use InvalidArgumentException;

final readonly class DocumentNumber
{
    private function __construct(public string $value, public string $type) {}

    public static function cpfOrCnpj(string $document): self
    {
        $digits = preg_replace('/\D+/', '', $document) ?? '';

        if (strlen($digits) === 11 && self::validCpf($digits)) {
            return new self($digits, 'cpf');
        }

        if (strlen($digits) === 14 && self::validCnpj($digits)) {
            return new self($digits, 'cnpj');
        }

        throw new InvalidArgumentException('CPF ou CNPJ inválido.');
    }

    private static function validCpf(string $value): bool
    {
        if (preg_match('/^(\d)\1{10}$/', $value) === 1) {
            return false;
        }

        for ($length = 9; $length <= 10; $length++) {
            $sum = 0;
            for ($index = 0; $index < $length; $index++) {
                $sum += (int) $value[$index] * (($length + 1) - $index);
            }

            $digit = ($sum * 10) % 11;
            $digit = $digit === 10 ? 0 : $digit;
            if ($digit !== (int) $value[$length]) {
                return false;
            }
        }

        return true;
    }

    private static function validCnpj(string $value): bool
    {
        if (preg_match('/^(\d)\1{13}$/', $value) === 1) {
            return false;
        }

        foreach ([12, 13] as $length) {
            $weights = $length === 12 ? [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2] : [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
            $sum = 0;
            foreach ($weights as $index => $weight) {
                $sum += (int) $value[$index] * $weight;
            }

            $remainder = $sum % 11;
            $digit = $remainder < 2 ? 0 : 11 - $remainder;
            if ($digit !== (int) $value[$length]) {
                return false;
            }
        }

        return true;
    }
}

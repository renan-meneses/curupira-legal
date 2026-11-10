<?php

declare(strict_types=1);

namespace App\Rules;

use App\Domain\Customers\DocumentNumber;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class ValidBrazilianDocument implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            $fail('O :attribute deve ser um texto.');
            return;
        }

        try {
            DocumentNumber::cpfOrCnpj($value);
        } catch (\InvalidArgumentException) {
            $fail('O :attribute informado é inválido.');
        }
    }
}

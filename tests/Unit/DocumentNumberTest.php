<?php

declare(strict_types=1);

use App\Domain\Customers\DocumentNumber;

it('normalizes and validates CPF', function (): void {
    $document = DocumentNumber::cpfOrCnpj('529.982.247-25');

    expect($document->value)->toBe('52998224725')
        ->and($document->type)->toBe('cpf');
});

it('normalizes and validates CNPJ', function (): void {
    $document = DocumentNumber::cpfOrCnpj('04.252.011/0001-10');

    expect($document->value)->toBe('04252011000110')
        ->and($document->type)->toBe('cnpj');
});

it('rejects invalid documents', function (): void {
    expect(fn () => DocumentNumber::cpfOrCnpj('111.111.111-11'))
        ->toThrow(\InvalidArgumentException::class);
});

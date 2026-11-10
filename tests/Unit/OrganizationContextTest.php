<?php

declare(strict_types=1);

use App\Domain\Organizations\OrganizationContext;

it('requires an active organization before reading its id', function (): void {
    $context = new OrganizationContext();

    expect(fn () => $context->id())->toThrow(\RuntimeException::class);
});

it('clears the active organization after the request lifecycle', function (): void {
    $context = new OrganizationContext();
    $context->set('01J9TENANT0000000000000000');

    expect($context->hasOrganization())->toBeTrue();
    $context->clear();
    expect($context->hasOrganization())->toBeFalse();
});

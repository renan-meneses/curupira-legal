<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Domain\Organizations\OrganizationContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureOrganizationContext
{
    public function __construct(private readonly OrganizationContext $context) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $organizationId = $request->header('X-Organization-Id');
        if ($organizationId === null && $request->hasSession()) {
            $organizationId = $request->session()->get('active_organization_id');
        }

        abort_unless($user !== null && is_string($organizationId), 403, 'Organização ativa não encontrada.');

        abort_unless(
            $user->organizations()->whereKey($organizationId)->exists(),
            403,
            'Você não possui acesso a esta organização.',
        );

        $this->context->set($organizationId);

        try {
            return $next($request);
        } finally {
            $this->context->clear();
        }
    }
}

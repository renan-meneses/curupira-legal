<?php

declare(strict_types=1);

namespace App\Domain\Audit;

use App\Models\AuditLog;

final class AuditLogger
{
    public function record(?string $organizationId, ?string $userId, string $action, ?string $type, ?string $id, array $metadata = [], ?string $ip = null, ?string $userAgent = null): AuditLog
    {
        return AuditLog::query()->create([
            'organization_id' => $organizationId,
            'user_id' => $userId,
            'action' => $action,
            'auditable_type' => $type,
            'auditable_id' => $id,
            'metadata' => $metadata,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'created_at' => now(),
        ]);
    }
}

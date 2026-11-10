<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Organizations\OrganizationRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

final class OrganizationMembership extends Model
{
    public $incrementing = false;
    use HasUlids;

    protected $keyType = 'string';

    protected $fillable = ['organization_id', 'user_id', 'role', 'permissions', 'joined_at'];

    protected function casts(): array
    {
        return ['role' => OrganizationRole::class, 'permissions' => 'array', 'joined_at' => 'datetime'];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function can(string $permission): bool
    {
        return (bool) ($this->permissions[$permission] ?? false);
    }
}

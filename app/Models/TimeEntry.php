<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\TimeTracking\TimeEntryStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

final class TimeEntry extends Model
{
    use HasFactory;
    use HasUlids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['organization_id', 'contract_id', 'user_id', 'worked_on', 'duration_minutes', 'billable', 'status', 'description', 'approved_by', 'approved_at', 'rejection_reason'];

    protected function casts(): array
    {
        return ['worked_on' => 'date', 'duration_minutes' => 'integer', 'billable' => 'boolean', 'status' => TimeEntryStatus::class, 'approved_at' => 'datetime'];
    }

    public function organization(): BelongsTo { return $this->belongsTo(Organization::class); }
    public function contract(): BelongsTo { return $this->belongsTo(Contract::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function approver(): BelongsTo { return $this->belongsTo(User::class, 'approved_by'); }
}

<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class BillingRecord extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['organization_id', 'billing_period_id', 'contract_id', 'idempotency_key', 'billing_model', 'gross_cents', 'calculation_snapshot', 'status', 'finalized_at'];
    protected function casts(): array { return ['gross_cents' => 'integer', 'calculation_snapshot' => 'array', 'finalized_at' => 'datetime']; }
    public function organization(): BelongsTo { return $this->belongsTo(Organization::class); }
    public function period(): BelongsTo { return $this->belongsTo(BillingPeriod::class, 'billing_period_id'); }
    public function contract(): BelongsTo { return $this->belongsTo(Contract::class); }
}

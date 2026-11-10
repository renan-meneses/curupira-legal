<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Billing\BillingPeriodStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

final class BillingPeriod extends Model
{
    public $incrementing = false;
    use HasUlids;
    protected $keyType = 'string';
    protected $fillable = ['organization_id', 'starts_on', 'ends_on', 'status'];
    protected function casts(): array { return ['starts_on' => 'date', 'ends_on' => 'date', 'status' => BillingPeriodStatus::class]; }
    public function organization(): BelongsTo { return $this->belongsTo(Organization::class); }
    public function records(): HasMany { return $this->hasMany(BillingRecord::class); }
}

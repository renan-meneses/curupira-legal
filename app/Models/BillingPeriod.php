<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class BillingPeriod extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['organization_id', 'starts_on', 'ends_on', 'status'];
    protected function casts(): array { return ['starts_on' => 'date', 'ends_on' => 'date']; }
    public function organization(): BelongsTo { return $this->belongsTo(Organization::class); }
    public function records(): HasMany { return $this->hasMany(BillingRecord::class); }
}

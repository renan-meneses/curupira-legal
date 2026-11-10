<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class TaxConfiguration extends Model
{
    use HasUlids;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['organization_id', 'service_code', 'issuer_municipality_code', 'customer_municipality_code', 'tax_regime', 'effective_from', 'effective_to', 'rules', 'status', 'reviewed_by', 'reviewed_at'];
    protected function casts(): array { return ['effective_from' => 'date', 'effective_to' => 'date', 'rules' => 'array', 'reviewed_at' => 'datetime']; }
    public function organization(): BelongsTo { return $this->belongsTo(Organization::class); }
    public function reviewer(): BelongsTo { return $this->belongsTo(User::class, 'reviewed_by'); }
}

<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class TaxCalculationSnapshot extends Model
{
    use HasUlids;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['organization_id', 'tax_configuration_id', 'source_type', 'source_id', 'competency_date', 'input_snapshot', 'result_snapshot'];
    protected function casts(): array { return ['competency_date' => 'date', 'input_snapshot' => 'array', 'result_snapshot' => 'array']; }
    public function organization(): BelongsTo { return $this->belongsTo(Organization::class); }
    public function configuration(): BelongsTo { return $this->belongsTo(TaxConfiguration::class, 'tax_configuration_id'); }
}

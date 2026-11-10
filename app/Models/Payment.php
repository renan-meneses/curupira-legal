<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Finance\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

final class Payment extends Model
{
    public $incrementing = false;
    use HasUlids;
    protected $keyType = 'string';
    protected $fillable = ['organization_id', 'receivable_id', 'registered_by', 'amount_cents', 'paid_on', 'method', 'external_reference', 'notes'];
    protected function casts(): array { return ['amount_cents' => 'integer', 'paid_on' => 'date', 'method' => PaymentMethod::class]; }
    public function organization(): BelongsTo { return $this->belongsTo(Organization::class); }
    public function receivable(): BelongsTo { return $this->belongsTo(Receivable::class); }
    public function registrar(): BelongsTo { return $this->belongsTo(User::class, 'registered_by'); }
}

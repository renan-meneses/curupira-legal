<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Finance\ReceivableStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

final class Receivable extends Model
{
    public $incrementing = false;
    use HasUlids;
    protected $keyType = 'string';
    protected $fillable = ['organization_id', 'billing_record_id', 'customer_id', 'due_on', 'amount_cents', 'paid_cents', 'status'];
    protected function casts(): array { return ['due_on' => 'date', 'amount_cents' => 'integer', 'paid_cents' => 'integer', 'status' => ReceivableStatus::class]; }
    public function organization(): BelongsTo { return $this->belongsTo(Organization::class); }
    public function billingRecord(): BelongsTo { return $this->belongsTo(BillingRecord::class); }
    public function customer(): BelongsTo { return $this->belongsTo(Customer::class); }
    public function payments(): HasMany { return $this->hasMany(Payment::class); }
}

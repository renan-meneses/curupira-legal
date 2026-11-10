<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Fiscal\FiscalInvoiceStatus;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class FiscalInvoice extends Model
{
    use HasUlids;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['organization_id', 'billing_record_id', 'tax_snapshot_id', 'provider', 'status', 'protocol', 'dps_payload', 'provider_response', 'rejection_reason', 'authorized_at'];
    protected function casts(): array { return ['status' => FiscalInvoiceStatus::class, 'dps_payload' => 'array', 'provider_response' => 'array', 'authorized_at' => 'datetime']; }
    public function organization(): BelongsTo { return $this->belongsTo(Organization::class); }
    public function billingRecord(): BelongsTo { return $this->belongsTo(BillingRecord::class); }
    public function taxSnapshot(): BelongsTo { return $this->belongsTo(TaxCalculationSnapshot::class, 'tax_snapshot_id'); }
    public function events(): HasMany { return $this->hasMany(FiscalInvoiceEvent::class); }
}

<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class FiscalInvoiceEvent extends Model
{
    use HasUlids;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['fiscal_invoice_id', 'event_type', 'status', 'payload', 'occurred_at'];
    protected function casts(): array { return ['payload' => 'array', 'occurred_at' => 'datetime']; }
    public function invoice(): BelongsTo { return $this->belongsTo(FiscalInvoice::class, 'fiscal_invoice_id'); }
}

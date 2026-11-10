<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Contracts\BillingModel;
use App\Domain\Contracts\ContractStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

final class Contract extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUlids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['organization_id', 'customer_id', 'title', 'description', 'contract_type', 'status', 'billing_model', 'starts_on', 'ends_on', 'hourly_rate', 'fixed_amount', 'payment_terms_days', 'service_conditions', 'renewal_rules', 'termination_rules'];

    protected function casts(): array
    {
        return [
            'status' => ContractStatus::class,
            'billing_model' => BillingModel::class,
            'starts_on' => 'date',
            'ends_on' => 'date',
            'hourly_rate' => 'decimal:2',
            'fixed_amount' => 'decimal:2',
            'payment_terms_days' => 'integer',
            'service_conditions' => 'array',
            'renewal_rules' => 'array',
            'termination_rules' => 'array',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(ContractVersion::class);
    }
}

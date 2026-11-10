<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

final class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUlids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['organization_id', 'name', 'document', 'email', 'phone', 'address', 'billing_contact', 'payment_terms_days', 'status'];

    protected function casts(): array
    {
        return ['address' => 'array', 'payment_terms_days' => 'integer'];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}

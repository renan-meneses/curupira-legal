<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

final class ContractVersion extends Model
{
    public $incrementing = false;
    use HasUlids;
    protected $keyType = 'string';
    protected $fillable = ['contract_id', 'created_by', 'version', 'change_type', 'snapshot', 'change_reason'];

    protected function casts(): array
    {
        return ['version' => 'integer', 'snapshot' => 'array'];
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

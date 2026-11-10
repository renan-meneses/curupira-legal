<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billing_records', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('billing_period_id')->constrained()->restrictOnDelete();
            $table->foreignUlid('contract_id')->constrained()->restrictOnDelete();
            $table->string('idempotency_key', 180);
            $table->string('billing_model', 32);
            $table->unsignedBigInteger('gross_cents');
            $table->jsonb('calculation_snapshot');
            $table->string('status', 24)->default('draft');
            $table->timestamp('finalized_at')->nullable();
            $table->timestamps();
            $table->unique(['organization_id', 'idempotency_key']);
            $table->index(['organization_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_records');
    }
};

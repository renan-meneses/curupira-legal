<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('customer_id')->constrained()->restrictOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('contract_type', 32);
            $table->string('status', 32)->default('draft');
            $table->string('billing_model', 32);
            $table->date('starts_on');
            $table->date('ends_on')->nullable();
            $table->decimal('hourly_rate', 15, 2)->nullable();
            $table->decimal('fixed_amount', 15, 2)->nullable();
            $table->unsignedSmallInteger('payment_terms_days')->default(0);
            $table->jsonb('service_conditions')->nullable();
            $table->jsonb('renewal_rules')->nullable();
            $table->jsonb('termination_rules')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['organization_id', 'status']);
            $table->index(['organization_id', 'customer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};

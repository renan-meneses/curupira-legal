<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_configurations', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('organization_id')->constrained()->cascadeOnDelete();
            $table->string('service_code', 32);
            $table->string('issuer_municipality_code', 7)->nullable();
            $table->string('customer_municipality_code', 7)->nullable();
            $table->string('tax_regime', 32)->nullable();
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->jsonb('rules');
            $table->string('status', 24)->default('draft');
            $table->foreignUlid('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->index(['organization_id', 'service_code', 'effective_from']);
            $table->index(['organization_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_configurations');
    }
};

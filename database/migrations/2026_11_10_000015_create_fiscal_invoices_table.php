<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fiscal_invoices', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('billing_record_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignUlid('tax_snapshot_id')->nullable()->constrained('tax_calculation_snapshots')->nullOnDelete();
            $table->string('provider', 64);
            $table->string('status', 24)->default('draft');
            $table->string('protocol', 180)->nullable();
            $table->jsonb('dps_payload')->nullable();
            $table->jsonb('provider_response')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('authorized_at')->nullable();
            $table->timestamps();
            $table->index(['organization_id', 'status']);
            $table->unique(['provider', 'protocol']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fiscal_invoices');
    }
};

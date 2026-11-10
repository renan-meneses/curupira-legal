<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_calculation_snapshots', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('tax_configuration_id')->constrained()->restrictOnDelete();
            $table->string('source_type')->nullable();
            $table->ulid('source_id')->nullable();
            $table->date('competency_date');
            $table->jsonb('input_snapshot');
            $table->jsonb('result_snapshot');
            $table->timestamps();
            $table->index(['organization_id', 'competency_date']);
            $table->index(['source_type', 'source_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_calculation_snapshots');
    }
};

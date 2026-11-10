<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('organization_id')->constrained()->cascadeOnDelete();
            $table->string('legal_name');
            $table->string('trade_name')->nullable();
            $table->string('document', 14);
            $table->string('municipal_registration')->nullable();
            $table->string('cnae')->nullable();
            $table->string('tax_regime', 32)->nullable();
            $table->string('ibge_municipality_code', 7)->nullable();
            $table->jsonb('address')->nullable();
            $table->jsonb('contact')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['organization_id', 'document']);
            $table->index(['organization_id', 'legal_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};

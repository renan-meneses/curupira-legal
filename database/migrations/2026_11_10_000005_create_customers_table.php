<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('document', 14);
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->jsonb('address')->nullable();
            $table->string('billing_contact')->nullable();
            $table->unsignedSmallInteger('payment_terms_days')->default(0);
            $table->string('status', 16)->default('active');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['organization_id', 'document']);
            $table->index(['organization_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

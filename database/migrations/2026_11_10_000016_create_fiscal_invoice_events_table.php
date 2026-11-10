<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fiscal_invoice_events', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('fiscal_invoice_id')->constrained()->cascadeOnDelete();
            $table->string('event_type', 32);
            $table->string('status', 24);
            $table->jsonb('payload')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();
            $table->index(['fiscal_invoice_id', 'occurred_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fiscal_invoice_events');
    }
};

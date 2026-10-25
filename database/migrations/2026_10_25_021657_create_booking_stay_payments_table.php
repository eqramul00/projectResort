<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_stay_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('recorded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Prevent the same payment form from being processed twice.
            $table->uuid('operation_key')->unique();

            // extension, overstay or settlement
            $table->string('type', 30);

            // Snapshots preserve what this payment related to.
            $table->date('previous_check_out')->nullable();
            $table->date('new_check_out')->nullable();

            $table->unsignedInteger('extra_nights')->default(0);
            $table->decimal('nightly_rate', 10, 2)->nullable();

            $table->decimal('amount', 10, 2);

            $table->string('payment_method', 30);
            $table->string('transaction_id', 100)->nullable();
            $table->text('notes')->nullable();

            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index([
                'booking_id',
                'type',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_stay_payments');
    }
};
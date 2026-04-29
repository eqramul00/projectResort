<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('status');
            $table->decimal('paid_amount', 10, 2)->default(0)->after('payment_method');
            $table->string('transaction_id')->nullable()->after('paid_amount');
            $table->text('payment_description')->nullable()->after('transaction_id');
            $table->string('payment_receipt')->nullable()->after('payment_description');
            $table->string('payment_status')->default('pending')->after('payment_receipt');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'paid_amount',
                'transaction_id',
                'payment_description',
                'payment_receipt',
                'payment_status',
            ]);
        });
    }
};
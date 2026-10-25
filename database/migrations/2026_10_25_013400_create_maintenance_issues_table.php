<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_issues', function (Blueprint $table) {
            $table->id();

            $table->foreignId('room_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('title', 150);
            $table->text('description')->nullable();

            $table->string('status')->default('open');
            $table->boolean('blocks_booking')->default(false);

            $table->date('starts_on');
            $table->date('ends_on');

            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index([
                'room_id',
                'blocks_booking',
                'status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_issues');
    }
};
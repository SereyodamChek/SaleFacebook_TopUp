<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // ✅ Owner of the order
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // ✅ Money
            $table->decimal('total_amount', 12, 2);
            $table->decimal('paid_amount', 12, 2);

            // ✅ Status
            $table->string('status')->default('paid');
            // paid | cancelled | refunded

            // Optional reference / note
            $table->string('reference')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

<?php

// database/migrations/xxxx_xx_xx_create_topups_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('topups', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->cascadeOnDelete();

      $table->decimal('amount', 12, 2);
      $table->string('currency', 10)->default('KHR'); // KHR or USD
      $table->string('status')->default('pending');   // pending|paid|failed|expired

      $table->text('qr')->nullable();
      $table->string('md5')->nullable()->index();

      $table->json('verify_payload')->nullable();     // store checkTransaction response
      $table->timestamp('paid_at')->nullable();

      $table->timestamps();
    });
  }

  public function down(): void {
    Schema::dropIfExists('topups');
  }
};

<?php

// database/migrations/xxxx_xx_xx_create_menu_categories_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('menu_categories', function (Blueprint $table) {
      $table->id();
      $table->string('group_key');          // product | recharge | association
      $table->string('title');              // e.g. "E-MAIL"
      $table->unsignedInteger('sort')->default(0);
      $table->boolean('is_active')->default(true);
      $table->timestamps();

      $table->index(['group_key', 'sort']);
    });
  }

  public function down(): void {
    Schema::dropIfExists('menu_categories');
  }
};

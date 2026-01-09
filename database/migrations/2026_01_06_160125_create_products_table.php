<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('products', function (Blueprint $table) {
      $table->id();

      // OPTIONAL: link product to menu item inside mega menu
      $table->foreignId('menu_item_id')->nullable()->constrained('menu_items')->nullOnDelete();

      $table->string('title');
      $table->decimal('price', 12, 2)->default(0);
      $table->unsignedInteger('stock')->default(0);
      $table->unsignedInteger('sold_out_amount')->default(0);
      $table->text('description')->nullable();

      $table->boolean('is_active')->default(true);

      $table->timestamps();

      $table->index(['menu_item_id', 'is_active']);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('products');
  }
};

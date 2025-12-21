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
        Schema::create('warehouse_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('quantity')->default(0);
            $table->string('unit')->default('قطعة'); // piece, meter, kg, etc.
            $table->decimal('price', 10, 2)->default(0.00);
            $table->integer('min_quantity')->default(10); // minimum quantity alert threshold
            $table->string('category')->nullable();
              $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_items');
    }
};

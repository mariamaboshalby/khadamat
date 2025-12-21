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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle_1')->nullable();
            $table->string('subtitle_2')->nullable();
            $table->string('badge_text')->nullable();
            $table->string('discount_value');
            $table->string('discount_label')->default('خصم');
            $table->string('gradient_class'); // promo-1, promo-2, promo-3
            $table->string('icon');
            $table->string('discount_color_class')->nullable(); // yellow, white, or null (default red)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};

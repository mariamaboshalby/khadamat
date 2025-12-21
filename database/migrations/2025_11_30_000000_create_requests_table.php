<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_technician_id')->nullable()->constrained('technicians')->onDelete('set null');
            $table->decimal('proposed_price', 10, 2)->nullable();
            $table->text('price_notes')->nullable();
            $table->enum('price_status', ['pending', 'accepted', 'rejected'])->nullable();
            $table->text('customer_notes')->nullable();
            $table->enum('status', ['pending', 'approved', 'pricing_pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->text('description')->nullable();
            $table->json('images')->nullable();
            $table->dateTime('scheduled_at')->nullable();
            $table->string('address');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};

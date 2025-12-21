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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->nullable()->constrained('requests')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');

            // Relations - make nullable for now to avoid FK issues
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('technician_id')->nullable();
            $table->unsignedBigInteger('service_id')->nullable();

            // Review Content
            $table->integer('rating'); // 1-5 stars
            $table->text('comment')->nullable();
            $table->string('title')->nullable();

            // Service Details
            $table->date('service_date')->nullable();
            $table->decimal('service_cost', 8, 2)->nullable();
            $table->string('service_type')->nullable(); // 'maintenance', 'installation', 'repair'

            // Status
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();

            // Metadata
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->string('ip_address', 45)->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['customer_id', 'technician_id']);
            $table->index(['rating', 'status']);
            $table->index(['service_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};

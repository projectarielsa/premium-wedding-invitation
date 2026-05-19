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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            
            // Order identification
            $table->string('order_number')->unique();
            $table->string('status')->default('pending');
            
            // Customer info (snapshot at order time)
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_whatsapp')->nullable();
            $table->date('wedding_date')->nullable();
            
            // Pricing
            $table->decimal('package_price', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total_price', 12, 2);
            $table->string('currency', 3)->default('IDR');
            
            // Payment info
            $table->string('payment_status')->default('unpaid');
            $table->string('payment_method')->nullable();
            $table->string('payment_proof')->nullable(); // File path
            $table->timestamp('payment_uploaded_at')->nullable();
            $table->text('payment_notes')->nullable();
            
            // Admin notes
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            
            // Timestamps for status changes
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            
            // Admin tracking
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete();
            
            // Metadata
            $table->json('metadata')->nullable(); // For storing additional data
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index(['status', 'created_at']);
            $table->index('payment_status');
            $table->index('order_number');
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

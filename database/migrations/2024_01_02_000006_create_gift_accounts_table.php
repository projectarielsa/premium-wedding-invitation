<?php

use App\Enums\GiftAccountType;
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
        Schema::create('gift_accounts', function (Blueprint $table) {
            $table->id();

            // Relationship
            $table->foreignId('invitation_id')
                ->constrained()
                ->cascadeOnDelete();

            // Account type
            $table->string('type')->default(GiftAccountType::BankTransfer->value);

            // Provider/Bank information
            $table->string('provider'); // BCA, Mandiri, GoPay, etc.
            $table->string('provider_logo')->nullable(); // Custom logo path

            // Account details
            $table->string('account_number')->nullable();
            $table->string('account_holder');

            // QR Code for QRIS/e-wallet
            $table->string('qr_image')->nullable(); // Path to QR image

            // Display settings
            $table->text('instructions')->nullable(); // Custom instructions for guests
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            // Usage tracking
            $table->unsignedBigInteger('copy_count')->default(0); // How many times copied
            $table->unsignedBigInteger('view_count')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('invitation_id');
            $table->index('type');
            $table->index('is_active');
            $table->index(['invitation_id', 'is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_accounts');
    }
};

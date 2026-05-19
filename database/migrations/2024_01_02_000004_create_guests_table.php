<?php

use App\Enums\GuestCategory;
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
        Schema::create('guests', function (Blueprint $table) {
            $table->id();

            // Relationship
            $table->foreignId('invitation_id')
                ->constrained()
                ->cascadeOnDelete();

            // Guest information
            $table->string('name');
            $table->string('phone_number')->nullable();
            $table->string('whatsapp')->nullable(); // Separate WhatsApp number if different
            $table->string('email')->nullable();

            // Categorization
            $table->string('category')->default(GuestCategory::Friend->value);

            // Personalized invitation URL token
            $table->uuid('slug_token')->unique();

            // QR Code for check-in
            $table->string('qr_code')->nullable(); // Path to QR code image

            // Attendance settings
            $table->unsignedTinyInteger('max_attendees')->default(2); // Max allowed plus-ones

            // Notes
            $table->text('notes')->nullable();

            // Tracking
            $table->unsignedInteger('unique_visit_count')->default(0);
            $table->timestamp('first_visited_at')->nullable();
            $table->timestamp('last_visited_at')->nullable();
            $table->timestamp('whatsapp_sent_at')->nullable();

            // Event check-in
            $table->timestamp('checked_in_at')->nullable();
            $table->string('checked_in_by')->nullable(); // Staff who checked in

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('invitation_id');
            $table->index('slug_token');
            $table->index('category');
            $table->index('phone_number');
            $table->index(['invitation_id', 'category']);
            $table->index(['invitation_id', 'created_at']);

            // Unique constraint: same phone number shouldn't be added twice per invitation
            $table->unique(['invitation_id', 'phone_number'], 'unique_guest_phone_per_invitation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};

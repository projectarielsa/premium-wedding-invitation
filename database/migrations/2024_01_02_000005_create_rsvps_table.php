<?php

use App\Enums\AttendanceStatus;
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
        Schema::create('rsvps', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('guest_id')
                ->unique() // One RSVP per guest
                ->constrained()
                ->cascadeOnDelete();

            // Denormalized invitation_id for faster queries
            $table->foreignId('invitation_id')
                ->constrained()
                ->cascadeOnDelete();

            // RSVP Response
            $table->string('attendance_status')->default(AttendanceStatus::Pending->value);
            $table->unsignedTinyInteger('attendance_count')->default(1); // How many people coming

            // Guest message/wishes
            $table->text('message')->nullable();

            // Dietary requirements or special needs
            $table->text('dietary_requirements')->nullable();
            $table->text('special_requests')->nullable();

            // Response tracking
            $table->timestamp('responded_at')->nullable();

            // Security & tracking
            $table->string('ip_address', 45)->nullable(); // IPv6 compatible
            $table->text('user_agent')->nullable();

            // Admin notes
            $table->text('admin_notes')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('invitation_id');
            $table->index('attendance_status');
            $table->index('responded_at');
            $table->index(['invitation_id', 'attendance_status']);
            $table->index(['invitation_id', 'responded_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rsvps');
    }
};

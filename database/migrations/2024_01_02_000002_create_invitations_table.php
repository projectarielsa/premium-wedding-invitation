<?php

use App\Enums\InvitationStatus;
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
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();

            // Ownership - Multi-tenant support
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Template relationship (nullable for custom designs)
            $table->foreignId('template_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Basic Information
            $table->string('title');
            $table->string('slug')->unique();

            // Couple Information
            $table->string('bride_name');
            $table->string('groom_name');
            $table->string('bride_parent')->nullable();
            $table->string('groom_parent')->nullable();

            // Content
            $table->text('opening_message')->nullable();
            $table->json('story_section')->nullable(); // Array of story blocks
            $table->string('cover_image')->nullable();
            $table->json('gallery')->nullable(); // Array of image paths

            // Media
            $table->string('music_url')->nullable();

            // Event details (legacy fields - use events table for multiple events)
            $table->date('event_date')->nullable();
            $table->string('location')->nullable();
            $table->string('google_maps_url')->nullable();
            $table->string('dress_code')->nullable();

            // Theme & Customization
            $table->json('theme_settings')->nullable(); // Override template colors/fonts
            $table->json('custom_css')->nullable(); // Advanced customization

            // SEO
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_image')->nullable(); // Open Graph image

            // Settings (JSON for flexibility)
            $table->json('settings')->nullable();
            /*
             * Expected settings structure:
             * {
             *   "rsvp_enabled": true,
             *   "gift_enabled": true,
             *   "guest_book_enabled": true,
             *   "countdown_enabled": true,
             *   "music_autoplay": false,
             *   "show_guest_count": true,
             *   "require_attendance_count": true,
             *   "max_attendance_per_guest": 5
             * }
             */

            // Publication status
            $table->string('status')->default(InvitationStatus::Draft->value);
            $table->timestamp('published_at')->nullable();

            // Analytics counters (denormalized for performance)
            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedBigInteger('unique_visitor_count')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Indexes for common queries
            $table->index('user_id');
            $table->index('status');
            $table->index('published_at');
            $table->index('event_date');
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};

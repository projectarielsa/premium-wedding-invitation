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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('badge')->nullable(); // e.g., "Popular", "Best Value"
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('original_price', 12, 2)->nullable(); // For discount display
            $table->string('currency', 3)->default('IDR');
            $table->integer('duration_days')->default(365); // Package validity in days
            
            // Feature Limits
            $table->integer('max_invitations')->default(1);
            $table->integer('max_guests_per_invitation')->default(100);
            $table->integer('max_events_per_invitation')->default(2);
            $table->integer('max_gift_accounts')->default(2);
            $table->integer('max_gallery_images')->default(10);
            
            // Feature Toggles
            $table->boolean('rsvp_enabled')->default(true);
            $table->boolean('gift_enabled')->default(false);
            $table->boolean('qr_checkin_enabled')->default(false);
            $table->boolean('analytics_enabled')->default(false);
            $table->boolean('custom_music_enabled')->default(false);
            $table->boolean('custom_domain_enabled')->default(false);
            $table->boolean('export_enabled')->default(false);
            $table->boolean('whatsapp_blast_enabled')->default(false);
            $table->boolean('guest_book_enabled')->default(true);
            $table->boolean('countdown_enabled')->default(true);
            $table->boolean('story_section_enabled')->default(false);
            $table->boolean('remove_watermark')->default(false);
            
            // Template Access
            $table->json('template_access')->nullable(); // null = basic only, ['all'] = all, ['slug1', 'slug2'] = specific
            
            // Support Level
            $table->string('support_level')->default('community'); // community, email, priority, dedicated
            $table->integer('support_response_hours')->nullable(); // null = no SLA
            
            // Display & Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->json('features_list')->nullable(); // Marketing features list for display
            
            $table->timestamps();
            
            $table->index(['is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};

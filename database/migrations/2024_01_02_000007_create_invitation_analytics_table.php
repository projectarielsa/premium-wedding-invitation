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
        Schema::create('invitation_analytics', function (Blueprint $table) {
            $table->id();

            // Relationship
            $table->foreignId('invitation_id')
                ->constrained()
                ->cascadeOnDelete();

            // Date for aggregation
            $table->date('date');

            // View metrics
            $table->unsignedInteger('page_views')->default(0);
            $table->unsignedInteger('unique_visitors')->default(0);

            // Engagement metrics
            $table->unsignedInteger('rsvp_submissions')->default(0);
            $table->unsignedInteger('gift_section_views')->default(0);
            $table->unsignedInteger('gift_copy_clicks')->default(0);
            $table->unsignedInteger('gallery_views')->default(0);
            $table->unsignedInteger('map_clicks')->default(0);

            // Sharing metrics
            $table->unsignedInteger('whatsapp_shares')->default(0);
            $table->unsignedInteger('link_copies')->default(0);

            // Guest tracking
            $table->unsignedInteger('guest_opens')->default(0); // Personalized link opens
            $table->unsignedInteger('anonymous_opens')->default(0); // Direct link opens

            // Device breakdown (JSON for flexibility)
            $table->json('device_stats')->nullable();
            /*
             * Expected structure:
             * {
             *   "mobile": 150,
             *   "desktop": 30,
             *   "tablet": 10
             * }
             */

            // Referral breakdown (JSON)
            $table->json('referral_stats')->nullable();
            /*
             * Expected structure:
             * {
             *   "whatsapp": 100,
             *   "instagram": 50,
             *   "direct": 30,
             *   "other": 10
             * }
             */

            // Browser breakdown (JSON)
            $table->json('browser_stats')->nullable();

            // Geographic breakdown (JSON)
            $table->json('location_stats')->nullable();

            $table->timestamps();

            // Unique constraint: one record per invitation per day
            $table->unique(['invitation_id', 'date'], 'unique_invitation_daily_analytics');

            // Indexes
            $table->index('invitation_id');
            $table->index('date');
            $table->index(['invitation_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitation_analytics');
    }
};

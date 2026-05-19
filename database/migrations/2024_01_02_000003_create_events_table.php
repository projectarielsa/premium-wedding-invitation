<?php

use App\Enums\EventType;
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
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            // Relationship
            $table->foreignId('invitation_id')
                ->constrained()
                ->cascadeOnDelete();

            // Event type
            $table->string('type')->default(EventType::Reception->value);
            $table->string('name'); // Custom name like "Akad Nikah" or "Wedding Reception"

            // Date and time
            $table->date('event_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('timezone')->default('Asia/Jakarta');

            // Venue information
            $table->string('venue_name')->nullable();
            $table->text('venue_address')->nullable();
            $table->string('google_maps_url')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Additional details
            $table->string('dress_code')->nullable();
            $table->text('notes')->nullable();

            // Display ordering
            $table->unsignedInteger('sort_order')->default(0);

            // Visibility
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('invitation_id');
            $table->index('event_date');
            $table->index('type');
            $table->index(['invitation_id', 'sort_order']);
            $table->index(['invitation_id', 'event_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};

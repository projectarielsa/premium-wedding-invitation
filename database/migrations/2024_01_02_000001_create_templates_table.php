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
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('preview_image')->nullable();
            $table->string('thumbnail_image')->nullable();

            // Theme configuration (colors, fonts, animations)
            $table->json('theme_config')->nullable();

            // Available sections configuration
            $table->json('sections')->nullable();

            // Template categorization
            $table->string('category')->default('premium'); // premium, free, exclusive
            $table->boolean('is_premium')->default(false);
            $table->boolean('is_active')->default(true);

            // Display ordering
            $table->unsignedInteger('sort_order')->default(0);

            // Usage tracking
            $table->unsignedBigInteger('usage_count')->default(0);

            $table->timestamps();

            // Indexes
            $table->index('is_active');
            $table->index('is_premium');
            $table->index('sort_order');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};

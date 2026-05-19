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
        Schema::table('users', function (Blueprint $table) {
            // Role field
            $table->string('role')->default('customer')->after('email');
            
            // Active package relationship
            $table->foreignId('active_package_id')->nullable()->after('role')
                ->constrained('packages')->nullOnDelete();
            
            // Package validity dates
            $table->timestamp('package_started_at')->nullable()->after('active_package_id');
            $table->timestamp('package_expires_at')->nullable()->after('package_started_at');
            
            // Admin specific fields
            $table->boolean('is_suspended')->default(false)->after('package_expires_at');
            $table->text('suspension_reason')->nullable()->after('is_suspended');
            
            // Profile enhancements
            $table->string('phone_number')->nullable()->after('avatar');
            $table->string('whatsapp')->nullable()->after('phone_number');
            
            $table->index('role');
            $table->index('active_package_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['active_package_id']);
            $table->dropIndex(['role']);
            $table->dropIndex(['active_package_id']);
            
            $table->dropColumn([
                'role',
                'active_package_id',
                'package_started_at',
                'package_expires_at',
                'is_suspended',
                'suspension_reason',
                'phone_number',
                'whatsapp',
            ]);
        });
    }
};

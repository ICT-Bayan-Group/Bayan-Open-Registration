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
        Schema::table('registrations', function (Blueprint $table) {
            // Update status enum to include new statuses
            $table->enum('status', ['pending', 'pending_verification', 'paid', 'failed', 'expired'])
                  ->default('pending')
                  ->change();

            // Add new columns for manual payment verification
            $table->string('payment_proof')->nullable()->after('status');
            $table->timestamp('payment_verified_at')->nullable()->after('payment_proof');
            $table->foreignId('payment_verified_by')->nullable()->constrained('users')->nullOnDelete()->after('payment_verified_at');
            $table->text('payment_note')->nullable()->after('payment_verified_by');
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropForeign(['payment_verified_by']);
            $table->dropColumn(['payment_proof', 'payment_verified_at', 'payment_verified_by', 'payment_note']);

            // Revert status enum
            $table->enum('status', ['pending', 'paid', 'failed', 'expired'])
                  ->default('pending')
                  ->change();
        });
    }
};

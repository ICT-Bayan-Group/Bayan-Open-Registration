<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            // Approval flow columns
            $table->enum('approval_status', [
                'draft',
                'submitted',
                'pending_review',
                'approved',
                'rejected',
                'paid',
            ])->default('draft')->index()->after('status');

            $table->text('rejection_reason')->nullable()->after('approval_status');
            $table->timestamp('approved_at')->nullable()->after('rejection_reason');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete()->after('rejected_at');
            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete()->after('approved_by');

            // Payment link token (unik, dikirim via email setelah approved)
            $table->string('payment_token')->nullable()->unique()->after('rejected_by');
            $table->timestamp('payment_token_expires_at')->nullable()->after('payment_token');

            // Per-member city validation result from OCR
            $table->json('ktp_city_valid')->nullable()->after('ktp_data');
            // ['valid' => true/false, 'city_raw' => 'KOTA BALIKPAPAN', 'valid_count' => 6]
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn([
                'approval_status',
                'rejection_reason',
                'approved_at',
                'rejected_at',
                'approved_by',
                'rejected_by',
                'payment_token',
                'payment_token_expires_at',
                'ktp_city_valid',
            ]);
        });
    }
};
